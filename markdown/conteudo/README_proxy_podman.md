[← Voltar ao índice principal (README.md)](../README.md)

---

# projeto55100 — Proxy de Rede no Build Docker/Podman

**Ambiente:** máquina/VM dentro da rede corporativa DETRAN, sem resolução de
DNS interno para os hosts externos usados pelo build (`registry.npmjs.org`,
repositórios `apt-get` da imagem `php:8.2-fpm`).

---

## 1. Sintoma

`docker-compose up -d --build` (via Podman) falhava no build da imagem do
serviço `node`, no passo `RUN npm install --production`:

```
npm error code ETIMEDOUT
npm error errno ETIMEDOUT
npm error network request to https://registry.npmjs.org/express failed, reason:
npm error network This is a problem related to network connectivity.
npm error network In most cases you are behind a proxy or have bad network settings.
```

A mesma causa também afeta o `apt-get update`/`apt-get install` do Dockerfile
do serviço `php`, que depende de acesso externo para instalar pacotes de
sistema e extensões PHP.

---

## 2. Causa

Nenhum dos dois Dockerfiles declarava as variáveis de proxy da rede DETRAN.
Sem elas, qualquer etapa de build que precise de internet (`apt-get`, `npm
install`, `composer`) tenta ir direto e trava até dar timeout.

---

## 3. Correção aplicada

Adicionadas as mesmas 3 linhas logo após o `FROM`, em ambos os Dockerfiles:

```dockerfile
# Configurar proxy da rede DETRAN (usando IP pois a VM não resolve DNS interno)
ENV http_proxy=http://10.XX.XX.XX:80
ENV https_proxy=http://10.XX.XX.XX:80
ENV no_proxy=localhost,127.0.0.1
```

Arquivos alterados:

| Arquivo                  | Etapa de build que dependia de internet         |
| ------------------------ | ----------------------------------------------- |
| `docker/php/Dockerfile`  | `apt-get update && apt-get install` + extensões |
| `docker/node/Dockerfile` | `npm install --production`                      |

Modelo de referência usado (mesmo padrão de proxy, mesma rede):
`C:\xampp\htdocs\php\loglab\detran\cakephp\diarias\docker\php\Dockerfile`

---

## 4. Verificação

Após a correção, `docker-compose up -d --build` completou os 5 containers do
`projeto55100 (compose)` com status `RUNNING` no Podman Desktop:
`codeigniter55100_mysql`, `codeigniter55100_adminer`, `codeigniter55100_php`,
`codeigniter55100_node`, `codeigniter55100_nginx`.

---

## 5. Checklist para novos serviços com build

Ao adicionar um novo serviço ao `docker-compose.yml` que tenha `build:` (ou
seja, gera imagem própria em vez de usar `image:` pronta) e que precise baixar
pacotes durante o build (`apt-get`, `npm`, `pip`, `composer`, etc.):

- [ ] Adicionar o mesmo bloco `ENV http_proxy` / `ENV https_proxy` / `ENV
      no_proxy` logo após o `FROM` do novo Dockerfile.
- [ ] Confirmar que a máquina/VM onde o build roda está de fato na rede DETRAN
      — fora dela, este proxy causa timeout em vez de resolver o problema.
- [ ] Serviços que só usam `image:` pronta (ex.: `mysql:8.0`, `adminer:latest`,
      `nginx:alpine`) não precisam do proxy: a imagem já vem pronta do
      registry, sem etapa de build local.

---

[← Voltar ao índice principal (README.md)](../README.md)
