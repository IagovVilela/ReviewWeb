faça # Loja – Material de Coleta de Avaliações

Este documento explica como foi implementada a **Loja** na plataforma: o que ela faz, quem usa e como configurar.

---

## O que é a Loja

A Loja é a área onde o **proprietário** oferece **material de coleta de avaliações** (suportes de mesa, adesivos, etc.) aos usuários. Os usuários podem **solicitar** esse material informando quantidades e uma mensagem opcional. O proprietário é notificado por **e-mail** e pode acompanhar e atender as solicitações no painel.

---

## Para o Proprietário

### Onde acessar

- **Menu:** **Loja** → **Gerenciar loja** (ou acesse diretamente `/store/products` e `/store/requests`).
- Apenas usuários com perfil **Proprietário** podem gerenciar produtos e ver solicitações.

### O que o proprietário faz

1. **Cadastrar produtos**
   - Em **Gerenciar loja** → **Produtos da loja**, clique em **Adicionar produto**.
   - Preencha: nome, descrição (opcional), preço (opcional), imagem (opcional), ordem de exibição e se o produto está ativo.
   - Produtos ativos aparecem no catálogo da loja para os usuários.

2. **Editar e excluir produtos**
   - Na lista de produtos, use **Editar** ou **Excluir** conforme necessário.

3. **Ver e atender solicitações**
   - Em **Gerenciar loja** → **Solicitações** (ou `/store/requests`), o proprietário vê todas as solicitações enviadas pelos usuários.
   - Cada solicitação mostra: **quem** pediu (nome e e-mail), **quando**, **itens** (produto × quantidade) e **mensagem** (se houver).
   - O proprietário pode:
     - Alterar o **status** (Pendente, Contatado, Concluído, Cancelado).
     - Clicar em **Contatar** para abrir o e-mail do usuário e combinar entrega/pagamento.

4. **Receber notificação por e-mail**
   - Sempre que um usuário **envia uma solicitação**, o sistema envia um **e-mail** para **todos os usuários com perfil Proprietário** cadastrados na plataforma.
   - O e-mail contém:
     - Nome e e-mail de quem solicitou
     - Data e hora
     - Tabela com itens (produto e quantidade)
     - Mensagem opcional do usuário
     - Link para a página **Solicitações** no painel
   - Assim o proprietário é avisado na hora, mesmo sem estar logado.

---

## Para o Usuário (quem solicita material)

### Onde acessar

- **Menu:** **Loja** (rota `/store`).

### O que o usuário faz

1. **Ver o catálogo**
   - A página da Loja mostra os produtos ativos com nome, descrição, preço (se houver) e imagem.

2. **Montar o pedido**
   - Para cada produto, o usuário informa a **quantidade** desejada.
   - Pode preencher uma **mensagem opcional** (observações ou pedido especial).

3. **Enviar solicitação**
   - Ao clicar em **Enviar solicitação**, o sistema:
     - Valida que pelo menos um item tem quantidade maior que zero.
     - Grava a solicitação no painel (visível em **Solicitações**).
     - Envia o e-mail de notificação para o(s) proprietário(s).
   - Uma mensagem de confirmação informa que o proprietário entrará em contato.

---

## Resumo do fluxo

| Etapa | Quem | Onde | O que acontece |
|-------|------|------|----------------|
| 1 | Proprietário | Gerenciar loja → Produtos | Cadastra/edita produtos (nome, descrição, preço, imagem, ativo). |
| 2 | Usuário | Loja (`/store`) | Vê o catálogo, informa quantidades e mensagem, clica em **Enviar solicitação**. |
| 3 | Sistema | Backend | Salva a solicitação e envia e-mail para todos os proprietários. |
| 4 | Proprietário | E-mail + Gerenciar loja → Solicitações | Recebe o e-mail e vê a solicitação no painel; altera status e contata o usuário. |

---

## Configuração de e-mail (para o e-mail chegar ao proprietário)

O e-mail de **nova solicitação** usa o mesmo sistema de e-mail transacional da plataforma (SendGrid e/ou Resend). Para que os e-mails cheguem na caixa do proprietário:

1. Configure o envio de e-mail conforme a documentação em **DOCS/06-SISTEMA-EMAIL** (variáveis como `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `SENDGRID_API_KEY` ou `RESEND_API_KEY`).
2. Certifique-se de que cada usuário **Proprietário** tem um **e-mail válido** cadastrado no perfil, pois o sistema envia a notificação para o e-mail de cada um.

Se o e-mail não for enviado (por falha de configuração ou de provedor), a solicitação **continua sendo salva** e aparece normalmente em **Solicitações**; apenas a notificação por e-mail pode falhar (erros são registrados em log).

---

## Resumo técnico (para o desenvolvedor)

- **Controller:** `StoreController` — `index()` (catálogo), `submitRequest()` (envio da solicitação + envio de e-mail aos proprietários).
- **Modelos:** `StoreProduct`, `StoreRequest` (com relação `user()`).
- **E-mail:** view `emails/store-request-notification.blade.php`; envio via `TransactionalEmailService` para todos os `User` com `role = 'proprietario'`.
- **Traduções:** chaves em `lang/pt_BR/store.php` e `lang/en_US/store.php` (incluindo `email_*` para o e-mail).

---

*Documento criado para o cliente – explicação da função Loja e notificação por e-mail ao proprietário.*
