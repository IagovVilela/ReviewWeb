# Guia Stripe – O que enviar para configurar a assinatura

Este guia é para você **encontrar na sua conta Stripe** os dados que precisamos para ativar o pagamento da assinatura na plataforma. São **4 itens**; você pode enviar por e-mail ou mensagem (com cuidado: a **chave secreta** não deve ser publicada em rede social ou site).

Acesse o painel do Stripe: **[https://dashboard.stripe.com](https://dashboard.stripe.com)** (faça login na sua conta).

---

## 1. Chave publicável e Chave secreta (API Keys)

**Onde fica:** no menu lateral, clique em **Developers** (Desenvolvedores) e depois em **API keys** (Chaves de API).

**O que enviar:**

| O que | Onde está na tela | Como copiar |
|-------|-------------------|-------------|
| **Chave publicável** (Publishable key) | Aparece logo no topo, escrita em cinza. Começa com `pk_test_` ou `pk_live_`. | Clique em **Reveal test key** ou use a chave ao lado de “Publishable key” e copie. |
| **Chave secreta** (Secret key) | Abaixo da chave publicável. Está oculta (••••••). Começa com `sk_test_` ou `sk_live_`. | Clique em **Reveal test key** ou **Reveal live key** para mostrar e depois copie. **Não compartilhe em lugar público.** |

- Use as chaves de **teste** (`pk_test_` e `sk_test_`) se ainda estiver testando.
- Use as chaves **ao vivo** (`pk_live_` e `sk_live_`) quando for colocar em produção.

Envie as duas: a publicável e a secreta.

---

## 2. ID do preço da assinatura (Price ID)

Esse é o “produto” que será cobrado todo mês (ex.: plano mensal da plataforma).

**Onde fica:** no menu lateral, clique em **Product catalog** → **Products** (ou acesse **[https://dashboard.stripe.com/products](https://dashboard.stripe.com/products)**).

**O que fazer:**

1. Se ainda não tiver um produto para a assinatura mensal, clique em **Add product** e crie um (ex.: nome “Assinatura mensal”, preço recorrente mensal no valor que combinaram).
2. Clique no **produto** que representa a assinatura mensal.
3. Na seção **Pricing**, você verá o(s) preço(s) (ex.: R$ 99,00 / month).
4. Ao lado do preço há um **ID** que começa com `price_` (ex.: `price_1ABC123xyz...`). Esse é o **Price ID**.

**O que enviar:** copie e envie esse **Price ID** completo (ex.: `price_1ABC123xyz...`).

---

## 3. Segredo do webhook (Signing secret)

Esse dado **só pode ser obtido depois** que a plataforma estiver no ar com uma URL fixa (ex.: `https://sua-plataforma.up.railway.app`). Quem for configurar o sistema vai te informar essa URL.

**Onde fica:** no menu lateral, clique em **Developers** → **Webhooks** (ou **[https://dashboard.stripe.com/webhooks](https://dashboard.stripe.com/webhooks)**).

**O que fazer:**

1. Clique em **Add endpoint** (Adicionar endpoint).
2. Em **Endpoint URL**, coloque a URL que te passaram, seguida de `/stripe/webhook`.  
   Exemplo: `https://sua-plataforma.up.railway.app/stripe/webhook`
3. Em **Events to send** (Eventos para enviar), escolha **Select events** e marque estes:
   - `checkout.session.completed`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.paid`
   - `invoice.payment_failed`
4. Clique em **Add endpoint**.
5. Depois que o endpoint for criado, clique nele na lista. Na tela de detalhes, em **Signing secret**, clique em **Reveal** e copie o valor (começa com `whsec_`).

**O que enviar:** esse **Signing secret** (valor que começa com `whsec_`).

**Observação:** Se a plataforma ainda não tiver URL definitiva, você pode enviar antes os itens 1 e 2. O item 3 (webhook) será configurado quando a URL estiver pronta.

---

## Resumo – O que enviar

| # | Nome no Stripe | O que é | Onde achar |
|---|----------------|--------|-------------|
| 1 | **Chave publicável** | Publishable key (`pk_test_` ou `pk_live_`) | Developers → API keys |
| 2 | **Chave secreta** | Secret key (`sk_test_` ou `sk_live_`) | Developers → API keys (Reveal) |
| 3 | **Price ID** | ID do preço mensal (ex.: `price_1ABC...`) | Product catalog → Products → [seu produto] → Pricing |
| 4 | **Signing secret** | Webhook secret (`whsec_...`) | Developers → Webhooks → Add endpoint → depois Reveal no endpoint criado |

Envie os 4 itens com os nomes acima (ex.: “Chave publicável: pk_…”, “Chave secreta: sk_…”, etc.) para que possamos configurar a assinatura na sua conta Stripe.

**Dúvidas?** Entre em contato com quem está configurando a plataforma.
