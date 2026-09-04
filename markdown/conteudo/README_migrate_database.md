[← Voltar ao índice principal (README.md)](../README.md)

---

# Migrations e CLI Spark (CodeIgniter 4)

Guia de uso do CLI `spark` e do sistema de **migrations** do CodeIgniter 4
neste projeto: como criar, rodar e reverter migrations, qual grupo de conexão
elas usam e como executá-las dentro do container do Podman.

---

> ## ⚠️ Primeiro comando — rodar antes de qualquer outra coisa
>
> ```bash
> podman exec codeigniter55100_php php spark migrate
> ```
>
> Esse é o **primeiro comando** deste guia: cria a tabela de controle
> `migrations` no banco `codeigniter55100_db` (ver [§5](#5-primeira-execução)).
> Sem essa tabela, nenhuma migration de negócio roda depois — é
> pré-requisito para tudo o resto (`make:migration`, novas tabelas, etc.).

---

## 1. O que é `spark`

`spark` é o CLI do CodeIgniter 4, em `src/spark` (raiz do backend). Toda
migration é rodada através dele — não existe execução de migration fora do
`spark`.

Dentro do container `codeigniter55100_php` (serviço `php` do
`docker-compose.yml`), o working dir já é `/var/www/html` (= `src/`), então
basta:

```bash
podman exec codeigniter55100_php php spark <comando>
```

Fora do container (host, via XAMPP), rode direto de dentro de `src/`:

```bash
php spark <comando>
```

**Atenção ao grupo de conexão:** rodando fora do container, `$default` cai nos
valores locais (`localhost`, banco vazio) — não conecta em nada útil sem as
env vars `DB_HOST`/`DB_PORT`/`DB_DATABASE`/`DB_USERNAME`/`DB_PASSWORD`
setadas manualmente no shell. Prefira sempre rodar dentro do container.

---

## 2. Comandos principais

| Comando                                    | O que faz                                                                                              |
| ------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `php spark make:migration NomeDaMigration` | Gera um novo arquivo de migration em `app/Database/Migrations/`.                                       |
| `php spark migrate`                        | Roda todas as migrations novas (ainda não aplicadas) contra o banco.                                   |
| `php spark migrate:status`                 | Lista todas as migrations e se já foram aplicadas (não altera nada — seguro rodar a qualquer momento). |
| `php spark migrate:rollback`               | Desfaz (`down()`) todas as migrations do último batch.                                                 |
| `php spark migrate:refresh`                | `rollback` seguido de `migrate` — recria o schema do zero.                                             |

Exemplo, dentro do container:

```bash
podman exec codeigniter55100_php php spark make:migration CreateUserUsersTable
podman exec codeigniter55100_php php spark migrate
podman exec codeigniter55100_php php spark migrate:status
```

---

## 3. Convenção de nome e configuração

Configurado em [`app/Config/Migrations.php`](../../app/Config/Migrations.php):

- `$enabled = true` — migrations habilitadas.
- `$table = 'migrations'` — nome da tabela de controle (guarda quais
  migrations já rodaram, por batch).
- `$timestampFormat = 'Y-m-d-His_'` — prefixo de timestamp no nome do
  arquivo gerado por `make:migration` (ex.:
  `2026-09-04-181423_CreateUserUsersTable.php`). **Não mude esse formato**
  sem necessidade: o migration runner só reconhece os formatos suportados
  pelo CI4 (`YmdHis_`, `Y-m-d-His_`, `Y_m_d_His_`).
- `$lock = false` — lock de execução concorrente desabilitado (padrão de
  dev; normalmente ligado em produção).

---

## 4. Grupo de conexão usado

Migrations usam `$defaultGroup` de
[`app/Config/Database.php`](../../app/Config/Database.php), que é `'default'`.

Esse grupo é preenchido em runtime, no construtor de `Database.php`, a partir
das env vars do container `php` (`DB_HOST`, `DB_PORT`, `DB_DATABASE`,
`DB_USERNAME`, `DB_PASSWORD`, definidas em `docker-compose.yml`) — nenhuma
credencial fica gravada no arquivo.

**Atenção:** os Models da aplicação usam `DB_GROUP_001` (definido em
`Config/Constants.php`), que resolve para `'codeigniter55100_mysql'` fora de
`habilidade.com` — um grupo diferente de `'default'`, mas populado pelas
mesmas env vars. Migrations e Models hoje apontam para grupos com nomes
diferentes, mas para o **mesmo banco** (`codeigniter55100_db`) em
desenvolvimento local/Docker.

---

## 5. Primeira execução

Rodar `php spark migrate` (ou até `migrate:status`) pela primeira vez cria a
tabela `migrations` no banco automaticamente — mesmo que `app/Database/
Migrations/` esteja vazia, sem nenhuma migration de negócio escrita ainda.
Isso é comportamento interno do `MigrationRunner` do CI4, não precisa de
nenhuma migration específica para "criar a tabela de controle".

---

## 6. Convenção de colunas padrão (obrigatória em toda tabela nova)

Toda migration de tabela nova neste projeto segue este padrão de colunas —
independente do domínio da tabela.

**Chave primária:**

```php
'id' => [
    'type'           => 'BIGINT',
    'auto_increment' => true,
],
// ...
$this->forge->addPrimaryKey('id');
```

`id` é **`BIGINT`** — não `INT` — **não assinado como unsigned** (sem a flag
`unsigned`), `NOT NULL`, `AUTO_INCREMENT`, chave primária. Toda coluna de
chave estrangeira que referencia um `id` (ex.: `user_manager_id`) também deve
ser `BIGINT`, para o tipo bater com a coluna referenciada.

**Timestamps (as três colunas, em toda tabela):**

```php
use CodeIgniter\Database\RawSql;

'created_at' => [
    'type'    => 'DATETIME',
    'null'    => true,
    'default' => new RawSql('CURRENT_TIMESTAMP'),
],
'updated_at' => [
    'type'    => 'DATETIME',
    'null'    => true,
    'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
],
'deleted_at' => [
    'type' => 'DATETIME',
    'null' => true,
],
```

- `created_at`/`updated_at`: `DATETIME` nullable, `DEFAULT CURRENT_TIMESTAMP`;
  `updated_at` ganha também `ON UPDATE CURRENT_TIMESTAMP` (via `RawSql`, único
  jeito de expressar essa cláusula no Forge do CI4 — não existe chave
  `on_update` no array de campo).
- `deleted_at`: `DATETIME` nullable, sem default (`NULL` até o soft delete
  acontecer). Gerenciado pelo Model (`useSoftDeletes = true`), não escrito à
  mão pela aplicação.

---

## 7. Exemplo real: grupo de tabelas de usuário

Primeiro grupo de migrations de negócio do projeto — 3 tabelas, nesta ordem
de dependência (revisado: `user_manager` e `user_credentials` eram a mesma
coisa e foram fundidas em uma tabela só):

1. **`user_manager`** — identidade **e** login, numa tabela só: `id`,
   `username` (único), `password_hash`, `token`, `status`
   (`active`/`inactive`/`blocked`), `last_login_at`, timestamps.
2. **`user_profiles`** — dados pessoais: FK `user_manager_id` →
   `user_manager.id`, `uuid` (chave pública temporária, reemitida a cada
   acesso via link público por e-mail), `name`, `phone`, `whatsapp`, `email`
   (único), `cpf`, `cep`, `address`, timestamps.
3. **`user_roles`** — definição de perfis de acesso (sem vínculo com usuário
   ainda — ver observação abaixo): `name`, `slug` (único), `description`,
   `permissions` (JSON — rotas, telas, botões, campos de formulário, colunas
   de tabela), `status`, timestamps.

Arquivos em `app/Database/Migrations/`:

```
2026-09-04-185437_CreateUserManagerTableMigration.php
2026-09-04-185440_CreateUserProfilesTableMigration.php
2026-09-04-185442_CreateUserRolesTableMigration.php
```

**Observação em aberto:** `user_roles` hoje é só uma tabela de definição de
perfis — nenhuma migration liga `user_manager` a um `role_id`. Falta decidir
se a atribuição é 1:1 (coluna `role_id` em `user_manager`) ou N:N (tabela
pivô `user_manager_roles`).

---

[← Voltar ao índice principal (README.md)](../README.md)
