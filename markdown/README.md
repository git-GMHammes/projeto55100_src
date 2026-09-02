# Documentação — Projeto55100 (`src/markdown/`)

> **Documento nº 1.** Índice central da documentação técnica que vive em
> `src/markdown/conteudo/`. Cada arquivo daquela pasta tem, na primeira e na
> última linha, um link de retorno para este README.
>
> **Como usar:** no [Índice](#índice) abaixo, cada item traz uma descrição de
> ~5 palavras e aponta para um **bloco de resumo** mais detalhado neste mesmo
> arquivo. Cada bloco de resumo termina com o link para o documento completo em
> `conteudo/`.
>
> **Ao adicionar, remover ou renomear** um arquivo em `conteudo/`, siga o
> [ROADMAP de Atualização deste README](#12-roadmap-de-atualização-deste-readme)
> (documento completo:
> [conteudo/ROADMAP_atualiza_readme.md](conteudo/ROADMAP_atualiza_readme.md)).

---

## Índice

**Ordem de leitura** — do mais primário ao mais acessório, na sequência de
planejamento e execução do projeto:

- **1–4** — entender o sistema e suas restrições (ler primeiro).
- **5** — pôr o ambiente para rodar.
- **6–9** — construir features (o trabalho diário).
- **10–11** — publicar.
- **12–14** — manutenção e troubleshooting situacional.
- **15–16** — referência de outro projeto (56400); ler por último.

1. [Referência de Portas e Serviços](#1-referência-de-portas-e-serviços) — Portas 551XX, dois ambientes, dependências.
2. [Arquitetura do Backend](#2-arquitetura-do-backend) — Camadas Controller, Service, Model, Request.
3. [Arquitetura do Frontend](#3-arquitetura-do-frontend) — SPA React 19, Vite, TypeScript.
4. [Segurança de Credenciais e Banco](#4-segurança-de-credenciais-e-banco-de-dados) — Segredos e vetores de vazamento.
5. [Guia de Desenvolvimento (Frontend)](#5-guia-de-desenvolvimento-frontend) — Quick start React, scripts, pré-requisitos.
6. [ROADMAP de Nova API (Tabela/View)](#6-roadmap-de-nova-api-para-tabela-ou-view) — Criar módulo REST completo.
7. [Modelo de Tela de Listagem](#7-modelo-de-tela-de-listagem-frontend) — Busca, tabela e modal.
8. [Campos de Formulário (`field/`)](#8-campos-de-formulário-field) — Field factories com validação brasileira.
9. [CORS no CodeIgniter 4 com Nginx](#9-cors-no-codeigniter-4-com-nginx) — Correção de CORS SPA↔API.
10. [Deploy em Produção](#10-deploy-em-produção) — Apache, FTP, build manual local.
11. [Deploy via Git de GitHub para KingHost](#11-deploy-via-git-de-github-para-kinghost) — Webhook, causa raiz, sequência de correção.
12. [ROADMAP de Atualização deste README](#12-roadmap-de-atualização-deste-readme) — Processo obrigatório ao mudar `conteudo/`.
13. [ROADMAP de Correção do Podman](#13-roadmap-de-correção-do-podman-em-starting) — Podman Desktop travado em STARTING.
14. [ROADMAP de podman compose](#14-roadmap-de-podman-compose-com-provider-externo) — Provider externo em vez do nativo.
15. [ROADMAP de Migração para CodeIgniter 4](#15-roadmap-de-migração-para-codeigniter-4) — Chat projeto56400, fases e checklist.
16. [ROADMAP de Migração para Laravel 11](#16-roadmap-de-migração-para-laravel-11) — Chat projeto56400, fases e checklist.

---

### 1. Referência de Portas e Serviços

Referência das **portas 551XX**, serviços e dependências do Projeto55100. É o
mapa do sistema: leia antes de qualquer outra coisa para saber o que roda onde.

Pontos-chave:

- Mapa de portas: 55100 (Nginx → PHP + React), 55101 (MySQL 8.0), 55102
  (Adminer); PHP-FPM 8.2 e Node.js WS internos.
- Dois ambientes: **dev local** (Docker Compose, com
  `docker-compose.override.yml` incluindo o `node`) e **simulação de produção**
  (`docker compose -f docker-compose.yml`).
- Nomes de containers e dependências entre serviços.

📄 Documento completo:
[conteudo/README_portas_servicos.md](conteudo/README_portas_servicos.md)

[↑ Índice](#índice)

---

### 2. Arquitetura do Backend

Descreve o backend **CodeIgniter 4 / PHP 8.2 / MySQL** organizado em camadas de
abstração progressiva: `Request HTTP → Nginx → PHP-FPM → Controller → Service
(Processor) → Model → MySQL`, com `Requests` para validação de entrada e
`Filters` para middleware (JWT, rate limiting).

Pontos-chave:

- Hierarquia de **Controllers** a partir de `BaseResourceTableController` /
  `BaseResourceViewController` — toda lógica comum mora nas classes base, nunca
  duplicada entre módulos.
- Hierarquia de **Services (Processors)** e de **Models** (`Services/V1/`,
  `Models/V1/`), responsabilidade única por camada.
- Rotas `/api/v1/...`, segurança/ofuscação, fluxo completo de uma requisição
  típica, padrão de criação de novo módulo e diagrama de classes.

📄 Documento completo:
[conteudo/README_arquitetura_backend.md](conteudo/README_arquitetura_backend.md)

[↑ Índice](#índice)

---

### 3. Arquitetura do Frontend

Descreve a **SPA React 19 + Vite 8 + TypeScript 6 + Bootstrap 5.3**, totalmente
desacoplada do backend e consumindo a API REST `/api/v1/` via `fetch` com Bearer
Token.

Pontos-chave:

- Estrutura de diretórios, roteamento, camada de serviços (API), páginas,
  componentes de UI (wrappers Bootstrap), hooks compartilhados (`useForm`,
  `useDebounce`), Context API (Auth, Theme) e store de estado global.
- 6 temas visuais, autenticação/sessão, configuração em `constants.ts` e
  `vite.config`.
- Fluxo de uma requisição típica, diagrama de arquitetura e padrão de criação de
  nova feature.

📄 Documento completo:
[conteudo/README_arquitetura_frontend.md](conteudo/README_arquitetura_frontend.md)

[↑ Índice](#índice)

---

### 4. Segurança de Credenciais e Banco de Dados

Mapeia o **escopo real de credenciais** do repositório: nenhuma string de
conexão, usuário, senha ou token versionado dá acesso a homologação ou produção
— apenas ao ambiente de desenvolvimento local. Restrição a internalizar antes de
escrever ou commitar código.

Pontos-chave:

- Ausência de `.env` real (o `src/.env` é só o template comentado do CI4).
- Credenciais em `docker-compose.yml` valem **exclusivamente** para o MySQL local
  (`127.0.0.1:55101`), nunca reutilizadas em ambientes reais.
- Texto técnico sobre **vetores de vazamento** de segredos (controle de versão,
  backups/nuvem, logs, servidor web mal configurado, supply chain, pipelines
  CI/CD, comprometimento de host, camadas de imagem, memória/swap, reutilização
  de senha) e práticas de mitigação.
- Recomendação final: rotacionar a senha do MySQL local publicada.

📄 Documento completo:
[conteudo/README_seguranca_banco.md](conteudo/README_seguranca_banco.md)

[↑ Índice](#índice)

---

### 5. Guia de Desenvolvimento (Frontend)

Guia de *quick start* do app React `Projeto55100App`
(`src/public/frontend/Projeto55100App/`).

Pontos-chave:

- Pré-requisitos (Node.js 18+, npm 9+), `npm install`, `cp .env.example .env`,
  `npm run dev` → `http://localhost:5173`.
- Scripts npm (`dev`, `build`, `preview`, `lint`) e suas URLs padrão.
- Stack e versões (React 19, TypeScript 6, Vite 8, React Router 7, Bootstrap
  5.3.8, D3, shpjs), 6 temas visuais e componentes de formulário com validação
  brasileira.

📄 Documento completo:
[conteudo/README_desenvolvimento.md](conteudo/README_desenvolvimento.md)

[↑ Índice](#índice)

---

### 6. ROADMAP de Nova API para Tabela ou View

Roteiro para criar **um módulo REST completo do zero** (modelo de referência:
`UserCustomer` / `user_002_customer`).

Pontos-chave:

- Três tipos de módulo: **Table** (`BaseResourceTableController` +
  `BaseTableService`, 17 endpoints), **View** (`BaseResourceViewController` +
  `BaseViewService`, 8 endpoints, só leitura) e **File** (Table + uploads).
- Convenções de nomes (feature em kebab, namespace PascalCase, tabela
  `prefixo_NNN_nome`, view `view_nome`, endpoints
  `/api/v1/feature[-view|-file]/...`).
- Estrutura de arquivos por módulo (Config/Routes, Controllers, Services, Models,
  Requests) e passo a passo de implementação, incluindo `handleInlineUpload()`.

📄 Documento completo:
[conteudo/ROADMAP_novo_plano_api_route.md](conteudo/ROADMAP_novo_plano_api_route.md)

[↑ Índice](#índice)

---

### 7. Modelo de Tela de Listagem (Frontend)

Modelo **replicável** de tela de listagem, usando "Municípios do RJ"
(`/#/v1/municipio-rj`) como referência: busca textual, tabela e modal de edição
completo por registro.

Pontos-chave:

- Convenção do projeto: **view SQL agregada para leitura/exibição** e **tabela
  crua para criação/edição**.
- Rota privada descentralizada por módulo (`routes/{Modulo}/PrivateRoutes.tsx`) +
  guarda `isAuthenticated()`; obrigatório registrar a entrada em `NAV_ITEMS` do
  `PrivateTopbar` para a tela aparecer no menu.
- Decomposição padrão: **página** (estado + orquestração) → **tabela de
  apresentação pura** → **hook de edição** → **modal de edição**.
- Fluxo de dados: carga inicial, busca com debounce de 400ms, edição via
  `getById` + `update`, checklist para novos módulos.

📄 Documento completo:
[conteudo/README_modelo_frontend_lista.md](conteudo/README_modelo_frontend_lista.md)

[↑ Índice](#índice)

---

### 8. Campos de Formulário (field/)

**Field Factory System** — sistema modular de *field factories* para construir
formulários HTML declarativos com validação em tempo real e formatação
brasileira (CPF, CNPJ, CEP, celular/telefone, monetário, SEI, datas, etc.).

Esta é a **única subpasta** de `conteudo/`. Os 15 documentos de componentes
(`README_input`, `README_cpf`, `README_cnpj`, `README_email`, `README_cep`,
`README_monetary`, `README_select`, `README_sei`, ...) ficam em
`conteudo/field/` e são listados pelo **sub-índice** próprio da pasta. Cada
documento cobre atributos padrão, classe de construção, `render()`, validação e
auto-execução (IIFE).

📄 Sub-índice (lista os 15 componentes):
[conteudo/field/README.md](conteudo/field/README.md)

[↑ Índice](#índice)

---

### 9. CORS no CodeIgniter 4 com Nginx

Diagnóstico e correção do erro `CORS Missing Allow Origin` entre a SPA React
(Vite, porta 5173) e a API CI4 servida via Nginx, quando a URL era chamada sem
`index.php`.

Pontos-chave:

- **Causa 1 (Nginx):** `try_files ... /index.php?$args` não preservava o
  `PATH_INFO` → CI4 recebia `/index.php` sem rota → 404.
- **Causa 2 (CI4):** o router roda **antes** dos filtros `globals.before`, então
  `OPTIONS` sem rota explícita lançava `PageNotFoundException` antes do filtro
  CORS.
- **Causa 3 (CI4):** filtro `cors` ausente em `globals.after`.
- **Solução:** Nginx intercepta `OPTIONS` retornando `204` com headers CORS;
  `try_files` passa a usar `/index.php$uri?$query_string`; `cors` em
  `globals.before` **e** `globals.after`. Inclui comandos de verificação
  (`curl`) e checklist para outros projetos.

📄 Documento completo:
[conteudo/README_cors_codeigniter4_nginx.md](conteudo/README_cors_codeigniter4_nginx.md)

[↑ Índice](#índice)

---

### 10. Deploy em Produção

Passo a passo do deploy do Projeto55100 no ambiente de produção: **Apache** com
`AllowOverride All`, **PHP 8.2+**, **sem Docker, sem Composer e sem Node.js no
servidor**, publicação por **FTP**.

Pontos-chave:

- Preparar `src/vendor/` localmente (`composer install --no-dev
  --optimize-autoloader`) e enviar junto.
- Buildar o frontend React localmente (`npm run build`) e enviar o `dist/`.
- Ordem de envio, ajustes de `.env` de produção e verificação pós-deploy.

📄 Documento completo:
[conteudo/README_deploy.md](conteudo/README_deploy.md)

[↑ Índice](#índice)

---

### 11. Deploy via Git de GitHub para KingHost

Fluxo de publicação automática via **Git** entre GitHub e a hospedagem KingHost
(`habilidade.com`), com diagnóstico, causa raiz e a sequência exata que resolveu
o problema em 02/06/2026.

Pontos-chave:

- Mapa de repositórios GitHub × branch × diretório no servidor KingHost
  (`~/www/projeto55100`), incluindo a diferença entre o caminho exibido no
  painel e o caminho real por SSH.
- Como funciona: `git push` → webhook do GitHub → processo interno da KingHost.
- Diagnóstico do problema e passos de correção.

📄 Documento completo:
[conteudo/README_deploy_git.md](conteudo/README_deploy_git.md)

[↑ Índice](#índice)

---

### 12. ROADMAP de Atualização deste README

Processo **obrigatório** de manutenção: como manter este `README.md` sincronizado
sempre que o conteúdo de `src/markdown/conteudo/` mudar.

Pontos-chave:

- Gatilhos: adicionar, remover, renomear ou mudar o escopo de um `.md` em
  `conteudo/`.
- Procedimento na mesma tarefa da mudança: link de retorno `../README.md` na
  primeira e última linha do arquivo; novo item no Índice (numeração + ~5
  palavras + âncora); novo bloco de resumo terminando com o link para o
  documento completo.
- Checklist de verificação de links e numeração, e registro da regra no
  `src/CLAUDE.md`.

📄 Documento completo:
[conteudo/ROADMAP_atualiza_readme.md](conteudo/ROADMAP_atualiza_readme.md)

[↑ Índice](#índice)

---

### 13. ROADMAP de Correção do Podman em STARTING

Diagnóstico e solução do **Podman Desktop preso em `STARTING`** indefinidamente
após reinicializações (Windows 11 · WSL 2 · Podman Desktop v1.27.2 · Podman
v5.8.2).

Pontos-chave:

- Causa: estado inconsistente do WSL 2, backend da máquina
  `podman-machine-default` (tipo `wsl`).
- Sequência de recuperação: `podman machine stop` → `wsl --terminate
  podman-machine-default` → `podman machine start` → conferir com `podman machine
  list` / `wsl --list --verbose` (menos de 1 minuto).
- Observações sobre o socket de compatibilidade Docker
  (`npipe:////./pipe/docker_engine`) e diagnósticos adicionais de virtualização.

📄 Documento completo:
[conteudo/ROADMAP_correcao_podman.md](conteudo/ROADMAP_correcao_podman.md)

[↑ Índice](#índice)

---

### 14. ROADMAP de podman compose com Provider Externo

Diagnóstico e solução para `podman compose up -d --build` falhando ao delegar
para o `docker-compose.exe` do Docker Desktop em vez do provider nativo.

Pontos-chave:

- `podman compose` não tem implementação própria: procura provider externo na
  ordem `podman-compose` (Python) → `docker-compose`. Com Docker Desktop
  instalado, cai no `docker-compose.exe`, que tenta o pipe
  `//./pipe/podman-machine-default` e falha.
- Solução: instalar/priorizar o `podman-compose` nativo (Python) no PATH.
- Inclui a anatomia do problema no Windows e as versões de referência
  (`python-dotenv`, `pyyaml`).

📄 Documento completo:
[conteudo/ROADMAP_podman_compose.md](conteudo/ROADMAP_podman_compose.md)

[↑ Índice](#índice)

---

### 15. ROADMAP de Migração para CodeIgniter 4

> **Escopo: projeto56400** (Chat em Tempo Real) — documento de referência de
> migração mantido junto da documentação do Projeto55100.

Roteiro planejado para migrar o chat do projeto56400 para **CodeIgniter 4.5.x**
sobre PHP 8.2-FPM (sem trocar a imagem Docker).

Pontos-chave:

- Stack atual × stack alvo, mapeamento de componentes e o que permanece intacto.
- Fases 0 a 8: branch, instalação via Composer, ambiente e banco,
  `MessageModel`, `MessageController`, rotas, ajustes de Nginx/Dockerfile PHP,
  front-end (`chat.js`) e testes.
- Checklist final e referências.

📄 Documento completo:
[conteudo/ROADMAP_codeigniter.md](conteudo/ROADMAP_codeigniter.md)

[↑ Índice](#índice)

---

### 16. ROADMAP de Migração para Laravel 11

> **Escopo: projeto56400** (Chat em Tempo Real) — documento de referência de
> migração mantido junto da documentação do Projeto55100.

Roteiro planejado para migrar o chat do projeto56400 para **Laravel 11.x**
(PHP 8.2+), sem trocar a imagem Docker.

Pontos-chave:

- Stack atual × stack alvo, mapeamento de componentes e o que permanece intacto.
- Fases 0 a 11: branch, instalação via Composer, ambiente e banco, migration
  `messages`, model Eloquent `Message`, `SendMessageRequest`,
  `MessageController`, rotas de API, CORS, ajustes de Nginx/Dockerfile PHP,
  front-end (`chat.js`) e testes.
- Comparável ao [ROADMAP de Migração para CodeIgniter 4](#15-roadmap-de-migração-para-codeigniter-4).

📄 Documento completo:
[conteudo/ROADMAP_laravel.md](conteudo/ROADMAP_laravel.md)

[↑ Índice](#índice)
