# Stripe – Passo a passo para achar cada dado

Use este guia se estiver com dificuldade para encontrar os dados no Stripe. Siga **um item por vez**.

**Importante:** Faça login em **[https://dashboard.stripe.com](https://dashboard.stripe.com)** antes de começar.

---

## 1. Chave secreta (Secret key)

**O que é:** Uma chave longa que começa com `sk_live_` (ou `sk_test_` em teste). O Stripe esconde ela com bolinhas (••••••) até você clicar para revelar.

**Passo a passo:**

1. No **menu da esquerda** do Stripe, role até encontrar **"Developers"** (ou **"Desenvolvedores"**).
2. Clique em **Developers**.
3. No submenu que aparecer, clique em **"API keys"** (ou **"Chaves de API"**).
   - **Link direto:** [https://dashboard.stripe.com/apikeys](https://dashboard.stripe.com/apikeys)
4. Você verá duas chaves:
   - **Publishable key** – já visível (começa com `pk_live_` ou `pk_test_`). Essa você já enviou.
   - **Secret key** – aparece como **••••••••••••** (oculta).
5. Ao lado de **"Secret key"** há um botão ou link escrito **"Reveal live key"** ou **"Reveal test key"**. **Clique nele.**
6. A chave completa vai aparecer (começa com `sk_live_` ou `sk_test_`). **Copie** e envie com o nome **"Chave secreta"**.

**Se não achar "Developers":** O menu fica na barra lateral esquerda. Às vezes aparece como ícone de engrenagem ou "Developer". Procure por "API keys" no menu.

---

## 2. Price ID (ID do preço da assinatura)

**O que é:** Um código que começa com `price_` (ex.: `price_1ABCdef123...`). É o “ID” do preço mensal que você cobra na assinatura.

**Passo a passo:**

1. No **menu da esquerda**, procure **"Product catalog"** (ou **"Catálogo de produtos"**).
2. Clique em **Product catalog** e depois em **"Products"** (Produtos).
   - **Link direto:** [https://dashboard.stripe.com/products](https://dashboard.stripe.com/products)
3. Você verá uma **lista de produtos**. Clique no **nome do produto** que representa a assinatura mensal da plataforma (ex.: "Assinatura mensal", "Plano mensal").
4. Abre a página **detalhes do produto**. Role até a parte **"Pricing"** (Preços).
5. Lá aparece o preço (ex.: "R$ 99,00 / month"). Ao lado ou abaixo do preço há um **código que começa com `price_`**. Pode estar em letras menores ou em um link "•••" ou "Details".
6. **Clique** nesse código ou em "..." para copiar, ou selecione e copie. Esse é o **Price ID**. Envie com o nome **"Price ID"**.

**Se ainda não tiver produto:** Na lista de Products, clique em **"Add product"**. Preencha o nome (ex.: "Assinatura mensal"), em Pricing escolha **Recurring** (Recorrente) e **Monthly** (Mensal), defina o valor e salve. Depois volte nesse produto e copie o Price ID que aparecer em "Pricing".

---

## 3. Signing secret do webhook (só quando tiver a URL da plataforma)

**O que é:** Um código que começa com `whsec_`. Só existe **depois** que você criar um "webhook" no Stripe usando a URL que quem configura a plataforma te passar.

**Quando fazer:** A URL da aplicação é **https://www.avalieganhe.app**. Use-a para criar o webhook e obter o Signing secret.

**Passo a passo (quando tiver a URL):**

1. No **menu da esquerda**, clique em **Developers** e depois em **"Webhooks"**.
   - **Link direto:** [https://dashboard.stripe.com/webhooks](https://dashboard.stripe.com/webhooks)
2. Clique no botão **"Add endpoint"** (ou **"Adicionar endpoint"**).
3. No campo **"Endpoint URL"** (ou **"URL do endpoint"**), digite **exatamente**:  
   **`https://www.avalieganhe.app/stripe/webhook`**
4. Em **"Events to send"**, escolha **"Select events"** e marque estes 5 eventos:
   - `checkout.session.completed`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.paid`
   - `invoice.payment_failed`
5. Clique em **"Add endpoint"** para salvar.
6. Você volta para a lista de webhooks. **Clique no endpoint que acabou de criar** (a URL que você colocou).
7. Na tela de detalhes, procure **"Signing secret"**. Está oculto (••••••). Clique em **"Reveal"** e **copie** o valor (começa com `whsec_`). Envie com o nome **"Signing secret"** ou **"Webhook secret"**.

---

## Resumo rápido – Onde clicar

| O que enviar      | Onde no Stripe                          | Link direto |
|-------------------|-----------------------------------------|-------------|
| Chave secreta     | Menu esquerda → **Developers** → **API keys** → Reveal em "Secret key" | [API keys](https://dashboard.stripe.com/apikeys) |
| Price ID          | Menu esquerda → **Product catalog** → **Products** → [seu produto] → seção **Pricing** | [Products](https://dashboard.stripe.com/products) |
| Signing secret    | Menu esquerda → **Developers** → **Webhooks** → Add endpoint → depois Reveal em "Signing secret" | [Webhooks](https://dashboard.stripe.com/webhooks) |

---

**Dúvidas?** Envie print da tela em que está travado para quem está configurando a plataforma.
