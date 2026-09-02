[← Voltar ao índice principal (README.md)](../README.md)

---

# Modelo de Tela de Listagem — Referência: Municípios do Rio de Janeiro

> URL de referência: `http://localhost:5173/#/v1/municipio-rj`
> Este documento descreve, em detalhe, a implementação da tela de listagem de
> Municípios do RJ para servir de **modelo replicável** em outras telas de
> listagem do sistema (ex.: candidatos, mandatários, outros cadastros com
> tabela + view).

---

## 1. Visão geral da tela

A tela exibe uma tabela de municípios do RJ com busca textual, botão de
atalho para o mapa (Home) e um modal de edição completo por registro. Todos
os dados de leitura vêm de uma **view SQL agregada** (`view_municipio_RJ`,
que já traz o nome do prefeito via JOIN com `mandatario_RJ`), enquanto a
edição/gravação atua diretamente sobre a **tabela crua** `municipio_RJ`.

Esse padrão — **view para leitura/exibição** e **tabela para
criação/edição** — é a convenção adotada em todo o projeto (ver
`BaseResourceViewController` vs. `BaseResourceTableController` no
`CLAUDE.md` raiz).

---

## 2. Rota e guarda de autenticação

Arquivo: `src/routes/Eleicao/PrivateRoutes.tsx`

```tsx
function PrivateMunicipioRJ() {
  return isAuthenticated() ? (
    <MunicipioRJList />
  ) : (
    <Navigate to="/v1/login" replace />
  );
}

const eleicaoPrivateRoutes = [
  { path: "/v1/municipio-rj", element: <PrivateMunicipioRJ /> },
  { path: "/v1/mandatario-rj", element: <PrivateMandatarioRJ /> },
  { path: "/v1/municipio-ibge-tse", element: <PrivateMunicipioIbgeTse /> },
];
```

- Rota privada: cada módulo declara seu próprio arquivo de rotas
  (`routes/{Modulo}/PrivateRoutes.tsx`), conforme convenção de "rotas
  descentralizadas" do `CLAUDE.md` do frontend.
- Guarda de acesso feita com `isAuthenticated()`
  (`services/modules/V1/authService/session`) — sem token válido, redireciona
  para `/v1/login`.
- O path segue a convenção `/v1/{feature-em-kebab-case}`, igual ao prefixo
  usado no backend (`/api/v1/municipio-rj`).

> ⚠️ **Registrar a rota não é suficiente para a tela ficar navegável.** O menu
> real do app é o Offcanvas acionado pelo hambúrguer do `PrivateTopbar`
> (`components/layout/PrivateTopbar/PrivateTopbar.tsx`), que lista as telas
> em um array fixo `NAV_ITEMS`:
>
> ```tsx
> const NAV_ITEMS = [
>   { path: '/v1/home', label: 'Início / Mapa RJ' },
>   { path: '/v1/municipio-rj', label: 'Municípios do Rio de Janeiro' },
>   { path: '/v1/mandatario-rj', label: 'Mandatários do Rio de Janeiro' },
>   { path: '/v1/municipio-ibge-tse', label: 'Municípios IBGE / TSE' },
> ]
> ```
>
> Toda nova tela de listagem **deve** adicionar sua entrada `{ path, label }`
> neste array — a tela `Home` não tem menu próprio, então esquecer este passo
> deixa a tela acessível apenas por URL direta, sem navegação visível.

---

## 3. Estrutura de arquivos do módulo

```
src/pages/Eleicao/MunicipioRJ/
├── index.ts
└── V1/List/
    ├── index.ts
    ├── MunicipioRJList.tsx        ← página principal (busca + tabela + modal)
    ├── MunicipioRJDataTable.tsx   ← tabela de exibição (apresentação pura)
    ├── useMunicipioRJEdit.ts      ← hook de estado/ações do modal de edição
    └── MunicipioRJEditModal.tsx   ← modal de edição (formulário completo)
```

Responsabilidade de cada arquivo:

| Arquivo                    | Responsabilidade                                                                                                                                                                                                                              |
| -------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `MunicipioRJList.tsx`      | Orquestra estado da página: busca (`query`), lista (`items`), `loading`/`error`, dispara `getAllView`/`searchView`, monta o schema do campo de busca e compõe `PrivateTopbar` + tabela + modal.                                               |
| `MunicipioRJDataTable.tsx` | Componente de apresentação pura: recebe `items`, `query` e `onEdit`; só formata e renderiza (`fmt`, `fmtNum`, `fmtDate`). Sem chamadas à API.                                                                                                 |
| `useMunicipioRJEdit.ts`    | Hook que isola o estado do fluxo de edição (`editId`, `editData`, `loadingEdit`, `saving`, `saveError`, `saveSuccess`) e as ações `handleEdit`/`handleSave`, incluindo abertura do modal Bootstrap via `bootstrap.Modal.getOrCreateInstance`. |
| `MunicipioRJEditModal.tsx` | Renderiza o formulário de edição (schema `FormGrid`) dentro do modal Bootstrap, incluindo campo dinâmico "Mandatário Extra" (alterna seleção de prefeito existente vs. texto livre).                                                          |

Esse é o padrão de decomposição a replicar: **página** (estado + orquestração)
→ **tabela de apresentação** → **hook de edição** → **modal de edição**.

---

## 4. Fluxo de dados

### 4.1 Carregamento inicial e busca (debounce)

```tsx
useEffect(() => {
  const timer = setTimeout(() => { void load(query) }, query ? 400 : 0)
  return () => clearTimeout(timer)
}, [query])

async function load(q: string) {
  const res = q.trim() ? await searchView(q.trim()) : await getAllView()
  ...
}
```

- Sem termo de busca: chama `getAllView()` imediatamente (delay 0).
- Com termo de busca: aguarda 400ms de debounce antes de chamar `searchView(q)`.
- Envelope de resposta (`ApiPageEnvelope`) é checado via `res.success` e
  `Array.isArray(res.data)` antes de popular `items`.
- Erros de rede são capturados e viram mensagem amigável em `error`.

### 4.2 Edição

```
usuário clica ✏️ → onEdit(id) → edit.handleEdit(id)
  → abre modal Bootstrap (getOrCreateInstance().show())
  → getByIdTable(id)  [GET /municipio-rj/get/{id}]  → popula editData
usuário edita e envia form → handleSave(e)
  → monta payload via FormData
  → updateTable(id, payload)  [PUT /municipio-rj/update/{id}]
  → em sucesso: saveSuccess = true + onReloadList() (recarrega a lista com a query atual)
```

- O reload após salvar reaproveita o `query` corrente (`useMunicipioRJEdit(() => void load(query))`),
  preservando o filtro ativo na tela.
- Campos vazios (`''`) do formulário são convertidos para `null` antes do envio.

### 4.3 Paginação (quando o volume de dados é grande)

`MunicipioRJList` usa um `limit` fixo alto (200) sem paginador, porque a
tabela de municípios do RJ tem poucas dezenas de linhas — carregar tudo de
uma vez é aceitável. **Isso não vale para tabelas potencialmente grandes**
(ex.: votos por município/candidato, que somam milhares de linhas). Nesses
casos a tela deve implementar paginação real:

```tsx
const [page, setPage] = useState(1)
const [pagination, setPagination] = useState<PaginationInfo | null>(null)

useEffect(() => { setPage(1) }, [query])          // busca nova → volta pra página 1
useEffect(() => {
  const timer = setTimeout(() => void load(query, page), query ? 400 : 0)
  return () => clearTimeout(timer)
}, [query, page])

async function load(q: string, p: number) {
  const res = q.trim() ? await searchView(q.trim(), p) : await getAllView(p)
  if (res.success && Array.isArray(res.data)) {
    setItems(res.data)
    setPagination(res.pagination ?? null)          // { page, limit, total, pages }
  }
}
```

- `getAllView`/`searchView` no service devem aceitar `page` (além de `limit`,
  default **50** para tabelas grandes) e repassar `page`/`limit` na query
  string — o backend já devolve `pagination` no envelope
  (`ApiPageEnvelope<T>` → `{ page, limit, total, pages }`).
- O componente `Pagination` (`components/ui/Pagination`) renderiza a barra de
  navegação a partir de `pagination.page`/`pagination.pages`, sem nenhuma
  lógica própria de fetch — ver seção 6.

---

## 5. Service HTTP (`services/modules/V1/municipioRJService.ts`)

Dois conjuntos de endpoints, com bases distintas:

```ts
const BASE_VIEW = `${APP_BASE_HOST}/api/v1/municipio-rj-view`;
const BASE_TABLE = `${APP_BASE_HOST}/api/v1/municipio-rj`;
```

| Função                     | Método/Endpoint                                                           | Uso na tela                             |
| -------------------------- | ------------------------------------------------------------------------- | --------------------------------------- |
| `getAllView(page, limit)`  | `GET /municipio-rj-view/get-all?page&limit&sort=mn_nome_cidade&order=asc` | Carga inicial da lista (sem busca)      |
| `searchView(q, limit)`     | `GET /municipio-rj-view/search?q&limit&sort=mn_nome_cidade&order=asc`     | Busca textual com debounce              |
| `getByIdTable(id)`         | `GET /municipio-rj/get/{id}`                                              | Carrega registro para o modal de edição |
| `updateTable(id, payload)` | `PUT /municipio-rj/update/{id}`                                           | Salva alterações do modal               |

Detalhes de implementação:

- `APP_BASE_HOST` e `APP_VERSION` vêm de `config/constants.ts` (troca
  automática entre `http://localhost:55100` em dev e o domínio de produção).
- `buildHeaders()` injeta `Authorization: Bearer {token}` via
  `getAuthHeader()` (sessão) em toda chamada — nenhuma rota deste módulo é
  pública.
- Tipos `MunicipioRJView` (prefixo `mn_` = município, `md_` = mandatário,
  todos os campos vindos do JOIN da view) e `MunicipioRJTable` (campos crus
  da tabela, sem prefixo, incluindo `mandatario_extra`/`observacao_mandato`)
  são mantidos separados — nunca misturar um tipo com o endpoint do outro.

---

## 6. Componentes de UI reaproveitados

- `PrivateTopbar` (`components/layout/PrivateTopbar`) — cabeçalho autenticado
  padrão (usuário logado + logout), usado em todas as telas privadas.
- `FormGrid` (`components/ui/FormGrid/Input`) — motor de formulário
  data-driven por `schema` (linhas → campos), usado tanto no campo único de
  busca quanto no formulário completo do modal. Suporta tipos `text`,
  `select` (com `src`/`valueKey`/`labelKey`/`authToken` para carregar opções
  de outro endpoint), `checkbox`, `textarea`, `data` (date), `inputMode`
  (`numeric`/`decimal`), `noNumbers`, `required`, `maxLength`.
- Tema visual (`themes/global` → `getActiveTheme().login`) fornece os
  gradientes de fundo/cabeçalho — reaproveitado em todas as listagens para
  manter consistência visual.
- Modal e tabela usam exclusivamente classes Bootstrap 5 (`table`,
  `table-hover`, `modal`, `spinner-border`, `alert`), sem CSS customizado.
- `Pagination` (`components/ui/Pagination`) — paginador Bootstrap genérico e
  reaproveitável, recebe apenas `currentPage`, `totalPages` e `onPageChange`
  via props (sem lógica de fetch/estado próprio). Usar sempre que a tela
  implementar paginação real (ver seção 4.3) em vez de recriar a barra de
  navegação a cada módulo.

---

## 7. Backend consumido (CodeIgniter 4)

### 7.1 Rotas

- View (somente leitura): `src/app/Config/Routes/Api/v1/Eleicao/MunicipioRJ/EndPointView.php`
  → prefixo `api/v1/municipio-rj-view/*`
- Tabela (leitura + escrita): `src/app/Config/Routes/Api/v1/Eleicao/MunicipioRJ/EndpointTable.php`
  → prefixo `api/v1/municipio-rj/*`

Ambas seguem o padrão REST completo de 14+ endpoints definido em
`BaseResourceTableController`/`BaseResourceViewController` (find,
get-grouped, search, get, get-all, get-no-pagination, get-deleted[-all],
get-with-deleted, get-all-with-deleted, create, update, delete-soft,
delete-restore, delete-hard, clear-deleted).

### 7.2 Controllers

- `ResourceViewController extends BaseResourceViewController` — apenas
  injeta `Processor` no `initController`; sem lógica própria (create/update
  bloqueados via hooks `final` da base).
- `ResourceTableController extends BaseResourceTableController` — injeta
  `Processor` e declara `getCreateRules()`/`getUpdateRules()` a partir de
  `Requests/V1/Eleicao/MunicipioRJ/CreateRequest.php` e `UpdateRequest.php`.

### 7.3 Service (`Services/V1/Eleicao/MunicipioRJ/Processor.php`)

Estende `BaseTableService`, usando dois models (`SqlTableModel` para a
tabela, `SqlViewModel` para a view). Sobrescreve apenas os hooks do
Template Method:

- `validateOnCreate` / `validateOnUpdate` — valida que
  `prefeito_mandatario_RJ_id` (se informado) existe em `mandatario_RJ`
  (via `SqlTableModel::existsByPrefeitoId`).
- `prepareData` / `prepareUpdateData` — normaliza datas
  (`aniversario_cidade`, `vice_dt_nascimento`,
  `primeira_dama_dt_nascimento`, `dt_festa_popular`, `data_emancipacao`)
  com `formatDate()` herdado de `BaseViewService`.

Nenhum endpoint, helper de resposta ou query CRUD é reescrito — tudo vem da
hierarquia base, exatamente como documentado no `CLAUDE.md` raiz.

---

## 8. Checklist para replicar este modelo em uma nova tela de listagem

1. **Backend**: criar `Models/V1/{Modulo}/SqlTableModel.php` +
   `SqlViewModel.php`, `Services/V1/{Modulo}/Processor.php` (estende
   `BaseTableService`, sobrescrevendo só os hooks necessários),
   `Controllers/Api/V1/{Modulo}/ResourceTableController.php` +
   `ResourceViewController.php`, `Requests/V1/{Modulo}/CreateRequest.php` +
   `UpdateRequest.php`, e as rotas em
   `Config/Routes/Api/v1/{Modulo}/EndpointTable.php` +
   `EndPointView.php` (prefixos `{feature}` e `{feature}-view`).
2. **Frontend — service**: criar
   `services/modules/V1/{feature}Service.ts` com `BASE_VIEW`/`BASE_TABLE`,
   tipos `{Feature}View`/`{Feature}Table`, e as funções
   `getAllView`/`searchView`/`getByIdTable`/`updateTable` (mais
   `create`/`delete*` se a tela precisar).
3. **Frontend — página**: criar
   `pages/{Dominio}/{Feature}/V1/List/{Feature}List.tsx` seguindo a mesma
   composição (`PrivateTopbar` + campo de busca via `FormGrid` + tabela +
   modal), com debounce de 400ms na busca.
4. **Frontend — tabela**: `​{Feature}DataTable.tsx` como componente de
   apresentação pura (sem chamadas à API), reaproveitando os helpers de
   formatação (`fmt`, `fmtNum`, `fmtDate`).
5. **Frontend — edição**: `use{Feature}Edit.ts` (hook de estado) +
   `{Feature}EditModal.tsx` (schema `FormGrid`), reaproveitando o padrão de
   abertura via `bootstrap.Modal.getOrCreateInstance`.
6. **Rota**: registrar em `routes/{Dominio}/PrivateRoutes.tsx` com path
   `/v1/{feature-kebab-case}`, idêntico ao prefixo da API.
7. **Menu**: adicionar `{ path: '/v1/{feature-kebab-case}', label: '...' }`
   ao array `NAV_ITEMS` em
   `components/layout/PrivateTopbar/PrivateTopbar.tsx`. Sem este passo a tela
   fica sem entrada no menu Offcanvas (hambúrguer) — só acessível digitando a
   URL diretamente.
8. **Sem API de Table → sem botões de escrita**: se a feature expuser
   **somente** a API de view (`ResourceViewController`/`BASE_VIEW`), sem uma
   API de tabela (`ResourceTableController`/`BASE_TABLE`) por trás, a tela de
   listagem **não deve** ter botão de editar na coluna "Ações", nem modal de
   edição, nem qualquer ação de `create`/`update`/`delete`. Nesse caso a tela
   é somente leitura: tabela de exibição + busca, sem `useXxxEdit.ts` nem
   `XxxEditModal.tsx`.
9. **Paginação**: se a tabela puder crescer muito (ex.: votos por
   município/candidato), implementar paginação real (ver seção 4.3) com o
   componente `Pagination` (`components/ui/Pagination`) e `limit` menor
   (ex.: 50), em vez de um `getAllView`/`searchView` com `limit` fixo alto
   sem paginador.
10. Nunca duplicar componentes Bootstrap genéricos — reaproveitar os já
    existentes em `components/ui/` e `components/layout/`.

---

[← Voltar ao índice principal (README.md)](../README.md)
