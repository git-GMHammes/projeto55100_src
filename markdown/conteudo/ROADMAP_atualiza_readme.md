[← Voltar ao índice principal (README.md)](../README.md)

---

# ROADMAP — Atualização do README principal

**Objetivo:** manter [`src/markdown/README.md`](../README.md) sempre sincronizado
com o conteúdo de `src/markdown/conteudo/`.

---

## 1. Estrutura vigente

- `src/markdown/README.md` é o **documento nº 1**: índice linkado + blocos de
  resumo detalhado.
- O índice tem **1 item por arquivo** de `src/markdown/conteudo/`, com descrição
  de **~5 palavras** e âncora `#` apontando para o bloco de resumo no próprio
  README.
- Cada **bloco de resumo** termina com o link para o documento completo em
  `conteudo/` (`📄 Documento completo: [conteudo/ARQUIVO.md](conteudo/ARQUIVO.md)`).
- Cada arquivo de `conteudo/` tem, na **primeira** e na **última** linha úteis, o
  link de retorno para o README principal. O caminho depende da profundidade:
  - arquivo direto em `conteudo/` → `[← Voltar ao índice principal (README.md)](../README.md)`
  - arquivo em subpasta `conteudo/<sub>/` → `[← Voltar ao índice principal (README.md)](../../README.md)`

### 1.1 Subpastas de `conteudo/`

Quando um conjunto de documentos forma um módulo próprio (ex.: `conteudo/field/`
— sistema de *field factories*), vale o padrão:

- A subpasta tem seu **próprio `README.md`**, que funciona como **sub-índice**
  daquele conjunto (lista e linka os documentos internos, com caminhos relativos
  à própria subpasta).
- O `src/markdown/README.md` principal ganha **um único item/bloco agrupado**
  para a subpasta (descrição de ~5 palavras + resumo), cujo link aponta para o
  sub-índice `conteudo/<sub>/README.md` — **não** se cria um bloco por documento
  interno.
- Todos os arquivos da subpasta (inclusive o sub-índice) levam o back-link
  `../../README.md` na primeira e na última linha.
- O cabeçalho do sub-índice deve declarar que é um sub-índice e apontar para
  `../../README.md`.

---

## 2. Gatilho

Qualquer uma destas ações em `src/markdown/conteudo/`:

- adicionar um novo `.md`;
- remover um `.md`;
- renomear um `.md`;
- alterar o escopo/assunto de um `.md` a ponto de mudar o resumo dele.

---

## 3. Procedimento (na mesma tarefa da mudança)

### 3.1 Ao adicionar um documento

1. Criar o arquivo em `src/markdown/conteudo/` no padrão de nome
   `README_<assunto>.md` ou `ROADMAP_<assunto>.md`.
2. Inserir, como **primeira linha** do arquivo, o link de retorno seguido de uma
   linha em branco e de `---`:
   - em `conteudo/` → `[← Voltar ao índice principal (README.md)](../README.md)`
   - em `conteudo/<sub>/` → `[← Voltar ao índice principal (README.md)](../../README.md)`
3. Inserir, como **última linha** do arquivo, a mesma linha de retorno.
4. Em `src/markdown/README.md`:
   - adicionar um item ao **Índice**, numerado, com descrição de ~5 palavras e
     âncora para o novo bloco;
   - adicionar o **bloco de resumo** correspondente (título `### N. ...`,
     parágrafo de contexto, pontos-chave e a linha
     `📄 Documento completo: [conteudo/ARQUIVO.md](conteudo/ARQUIVO.md)`,
     seguida de `[↑ Índice](#índice)`);
   - conferir que a âncora usada no índice corresponde ao título do bloco
     (minúsculas, espaços viram `-`, pontuação removida).

### 3.2 Ao remover um documento

1. Excluir o arquivo de `conteudo/`.
2. Remover o item do Índice e o bloco de resumo em `README.md`.
3. Renumerar os itens/blocos seguintes e revisar as âncoras.

### 3.3 Ao renomear um documento

1. Renomear o arquivo.
2. Atualizar o link `📄 Documento completo:` no bloco de resumo e o texto/âncora
   do item no Índice, se necessário.
3. Atualizar qualquer referência cruzada em outros documentos de `conteudo/`.

---

## 4. Checklist de verificação

- [ ] O `.md` novo/alterado tem o link de retorno na primeira e na última linha,
      com o caminho certo para a profundidade (`../README.md` na raiz de
      `conteudo/`, `../../README.md` em subpasta).
- [ ] O Índice do `README.md` tem 1 item por arquivo direto de `conteudo/` e 1
      item agrupado por subpasta (a contagem bate).
- [ ] Subpasta nova tem `README.md` sub-índice, e o item no `README.md` principal
      aponta para esse sub-índice.
- [ ] Cada item do Índice tem descrição de ~5 palavras.
- [ ] Cada item do Índice aponta para uma âncora existente no próprio
      `README.md`.
- [ ] Cada bloco de resumo termina com o link para o `.md` em `conteudo/`.
- [ ] A numeração dos itens e dos blocos está contínua e igual.
- [ ] Nenhum link quebrado (verificação manual ou por lint de Markdown).

---

## 5. Registro no CLAUDE.md

O `src/CLAUDE.md` referencia este ROADMAP na seção *"Documentação do projeto —
manutenção obrigatória"*. Toda tarefa que toque `src/markdown/conteudo/` deve
seguir este procedimento.

---

[← Voltar ao índice principal (README.md)](../README.md)
