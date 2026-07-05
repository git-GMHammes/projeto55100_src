# Índice da Documentação — Projeto55100

> Este é o documento central de navegação da documentação técnica do
> projeto. Todos os arquivos em `src/markdown/conteudo/` possuem, no início
> e no fim, um link de retorno para este README — o documento número um.

## Documentação Técnica (`src/markdown/conteudo/`)

1. [Arquitetura do Backend](markdown/conteudo/README_arquitetura_backend.md) — Hierarquia de Controllers/Services/Models do CodeIgniter 4, Requests, Filters, rotas, segurança e fluxo completo de uma requisição.
2. [Arquitetura do Frontend](markdown/conteudo/README_arquitetura_frontend.md) — Estrutura do SPA React 19 + Vite: roteamento, camada de serviços, páginas, componentes de UI, hooks, contexts, autenticação e padrão de criação de nova feature.
3. [CORS — CodeIgniter 4 + Nginx](markdown/conteudo/README_cors_codeigniter4_nginx.md) — Diagnóstico e correção de erro de CORS entre a SPA React (Vite) e a API CodeIgniter 4 servida via Nginx.
4. [Modelo de Tela de Listagem (Frontend)](markdown/conteudo/README_modelo_frontend_lista.md) — Modelo replicável de tela de listagem (busca + tabela + modal de edição), com checklist para criar novos módulos seguindo o mesmo padrão.
5. [Segurança de Credenciais e Banco de Dados](markdown/conteudo/README_seguranca_banco.md) — Escopo real de credenciais do projeto, ausência de `.env`, riscos técnicos de vazamento de segredos e recomendações de mitigação.
6. [ROADMAP — Correção do Podman travado em STARTING](markdown/conteudo/ROADMAP_correcao_podman.md) — Diagnóstico e correção de estado inconsistente entre WSL 2 e Podman Desktop.
7. [ROADMAP — Nova API para Tabela ou View](markdown/conteudo/ROADMAP_novo_plano_api_route.md) — Roteiro para criar um novo módulo REST completo (Table/View/File) do zero, com convenções de nomes e camadas.
8. [ROADMAP — Podman Compose](markdown/conteudo/ROADMAP_podman_compose.md) — Diagnóstico e solução do `podman compose` usando o provedor externo `docker-compose.exe` em vez do nativo.

---

# CodeIgniter 4 Framework

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds the distributable version of the framework.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Contributing

We welcome contributions from the community.

Please read the [*Contributing to CodeIgniter*](https://github.com/codeigniter4/CodeIgniter4/blob/develop/CONTRIBUTING.md) section in the development repository.

## Server Requirements

PHP version 8.2 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - The end of life date for PHP 8.1 was December 31, 2025.
> - If you are still using below PHP 8.2, you should upgrade immediately.
> - The end of life date for PHP 8.2 will be December 31, 2026.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
