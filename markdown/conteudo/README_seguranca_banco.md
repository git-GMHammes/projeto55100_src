[← Voltar ao índice principal (README.md)](../../README.md)

---

# Segurança de Credenciais e Banco de Dados — Projeto55100

**Data:** 2026-07-05

---

## 1. Escopo real de credenciais deste projeto

Este projeto **não possui, em nenhum arquivo do repositório, credenciais de
acesso a ambiente real de Homologação ou Produção**. Nenhuma string de
conexão, usuário, senha ou token presente no código-fonte versionado dá
acesso a qualquer sistema além do ambiente de desenvolvimento local
(Docker/Laragon na máquina do desenvolvedor).

---

## 2. Ausência de arquivo `.env`

Este repositório **não possui nenhum arquivo `.env`** (nem `.env.local`, nem
`.env.production`, nem variantes) com valores reais preenchidos. O único
arquivo com esse nome presente no backend (`src/.env`) contém apenas o
template de exemplo padrão do CodeIgniter 4, com todas as linhas comentadas
e valores de placeholder (`root`/`root`) — não é usado em tempo de execução
e não carrega nenhum segredo real.

---

## 3. `docker-compose.yml` — uso exclusivo de desenvolvimento

As credenciais presentes em `docker-compose.yml`
(`MYSQL_ROOT_PASSWORD`, `MYSQL_USER`, `MYSQL_PASSWORD`) referem-se
**exclusivamente ao container MySQL local de desenvolvimento**, que roda
apenas na máquina do desenvolvedor e escuta somente em `127.0.0.1:55101`,
sem exposição à internet. Essas credenciais:

- **Nunca** foram e **nunca** devem ser reaproveitadas em qualquer ambiente
  real (homologação, produção, ou qualquer serviço de hospedagem externo).
- Não têm relação alguma com as credenciais reais de produção do projeto,
  hospedadas em serviço de hospedagem externo, que são inteiramente
  distintas e nunca estiveram neste repositório.
- Servem apenas para permitir que qualquer pessoa clone o repositório e
  suba o ambiente de desenvolvimento localmente com `docker compose up -d`,
  sem precisar solicitar credenciais a ninguém — esse é o único motivo de
  estarem em texto simples neste arquivo.

---

## 4. Texto técnico — riscos de vazamento de dados via `.env` em qualquer ambiente

Arquivos `.env` são o mecanismo mais comum para injetar configuração e
segredos em aplicações, um padrão amplamente adotado na indústria de
software para separar configuração de código-fonte. Apesar de amplamente
adotado, um `.env` **não é, por si só, um mecanismo de segurança** — é
apenas uma forma de organizar essa separação. Os riscos abaixo se aplicam a
**qualquer projeto**, não só a este, e valem tanto para desenvolvimento
quanto — com gravidade muito maior — para homologação e produção.

**a) Exposição via controle de versão**
O erro mais comum é commitar o `.env` por engano (adicionar arquivos ao
versionamento sem revisar, ausência de regra de exclusão configurada, ou
configurada tarde demais). Uma vez commitado, o segredo permanece no
**histórico do repositório para sempre**, mesmo que o arquivo seja removido
em um commit posterior — só a reescrita completa do histórico mais rotação
de credenciais resolve, e só reduz exposição futura, não desfaz o que já
foi clonado, indexado ou cacheado por terceiros (robôs automatizados
escaneiam repositórios públicos em segundos após cada envio de código).

**b) Exposição via backups e sincronização em nuvem**
`.env` dentro de uma pasta sincronizada por um serviço de armazenamento em
nuvem, ou incluído em backups completos de servidor/imagens de disco,
replica o segredo para sistemas de terceiros fora do controle direto da
equipe.

**c) Exposição via logs e mensagens de erro**
Frameworks mal configurados (modo de depuração ativo em produção, stack
traces expostos, middlewares de log que despejam variáveis de ambiente
inteiras) podem vazar o conteúdo do `.env` em logs de aplicação, logs de
erro do servidor web, ou até em respostas HTTP de erro visíveis
publicamente.

**d) Exposição via servidor web mal configurado**
Se o servidor web não bloquear explicitamente arquivos ponto (`.env`,
`.git/`, `.htaccess`), uma requisição direta a `https://dominio.com/.env`
pode servir o arquivo como texto puro para qualquer visitante — um dos
vetores de vazamento mais comuns em produção real.

**e) Exposição via ataques de cadeia de suprimentos (supply chain)**
Qualquer dependência instalada via gerenciador de pacotes roda código
arbitrário no ambiente do desenvolvedor ou do servidor de build/CI. Uma
dependência maliciosa ou comprometida (ataque de typosquatting, mantenedor
comprometido, dependência transitiva envenenada) pode ler as variáveis de
ambiente do processo e exfiltrar o conteúdo do `.env` para um servidor
remoto sem qualquer sinal visível para o desenvolvedor.

**f) Exposição via pipelines de integração/entrega contínua**
Variáveis de ambiente injetadas em pipelines automatizados de build/deploy
podem vazar em logs de build se impressas por engano, ou ficar acessíveis a
qualquer colaborador com permissão de leitura no pipeline, mesmo sem acesso
ao repositório de código.

**g) Exposição por comprometimento do host/servidor**
Se um atacante obtém execução de código ou acesso ao sistema de arquivos do
servidor (via vulnerabilidade na aplicação, credencial de acesso remoto
fraca, serviço desatualizado), o `.env` é apenas mais um arquivo de texto
legível — nenhuma "ofuscação" de nome de arquivo, permissão Unix padrão
(644) ou localização "escondida" impede a leitura por quem já tem esse
nível de acesso. Isso vale igualmente para qualquer variação do padrão
(arquivos com nomes disfarçados, constantes com nomes aleatórios,
codificação base64): nenhuma dessas técnicas é criptografia — são apenas
ofuscação, reversível em segundos por qualquer pessoa com acesso de
leitura ao arquivo.

**h) Exposição via imagens de container**
Se o `.env` for copiado para dentro de uma imagem de container (em vez de
injetado em tempo de execução via variável de ambiente do orquestrador), o
segredo fica embutido em uma camada da imagem — qualquer pessoa com acesso
ao repositório de imagens (mesmo privado, mesmo após removido em uma camada
posterior) pode extraí-lo inspecionando as camadas da imagem.

**i) Exposição via memória, swap e core dumps**
Segredos carregados em variáveis de ambiente do processo ficam visíveis a
qualquer usuário com permissão de leitura no espaço de processo do sistema
operacional enquanto o processo roda, e podem persistir em arquivos de swap
ou em um despejo de memória gerado após um crash da aplicação.

**j) Reutilização de credenciais entre serviços/ambientes**
O erro mais caro em termos de impacto: usar a mesma senha para banco de
dados, e-mail e painel de hospedagem. Um único vazamento (de qualquer um
dos vetores acima) compromete todos os serviços simultaneamente. Este
projeto evita esse erro — as credenciais de desenvolvimento, produção e
e-mail são todas distintas entre si (verificado nesta sessão).

**Práticas recomendadas para mitigar (nenhuma delas oferece garantia
absoluta, mas reduzem risco e limitam o estrago quando um vazamento
ocorre):**

- Nunca commitar `.env` real — configurar a regra de exclusão de
  versionamento desde o primeiro commit do projeto, validando o que será
  enviado antes de cada commit.
- Usar um gerenciador de segredos dedicado em produção (serviço
  especializado em nuvem) em vez de arquivo estático, quando o ambiente
  permitir.
- Quando só um arquivo local for viável (ex.: hospedagem compartilhada sem
  suporte a `.env`, como já documentado neste projeto), mantê-lo **fora da
  raiz pública servida pelo webserver** e bloqueado explicitamente na
  configuração do servidor web.
- Rodar ferramentas automatizadas de escaneamento de segredos no
  repositório periodicamente, inclusive no histórico completo.
- Nunca reutilizar a mesma senha entre serviços/ambientes diferentes.
- Rotacionar credenciais periodicamente e sempre que houver qualquer
  suspeita de exposição, independentemente de confirmação.
- Restringir acesso de rede ao banco de dados por IP sempre que a
  hospedagem permitir, em vez de depender só da senha como barreira.
- Ativar autenticação multifator (MFA) nos painéis de hospedagem, no
  serviço de versionamento de repositórios e no e-mail associados ao
  projeto.

---

## 5. Recomendação final

As credenciais atualmente presentes em `docker-compose.yml` **podem e devem
ser alteradas** — trocar a senha do MySQL local de desenvolvimento não tem
custo funcional (basta atualizar o `docker-compose.yml` e reiniciar os
containers) e reforça a postura de segurança deste projeto para fins de
análise técnica e apresentação do sistema, removendo qualquer valor de
credencial hoje público no serviço de versionamento de repositórios.

---

[← Voltar ao índice principal (README.md)](../../README.md)
