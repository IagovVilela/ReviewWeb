# Deploy no Railway – Checklist

Este guia garante que o sistema está pronto para deploy no Railway **após as implementações atuais** (trial, loja, notificações por e-mail, assinatura Stripe, etc.).

---

## Dados do Stripe (conta do cliente) – o que pedir ao cliente

Para usar a **conta Stripe do cliente** (os pagamentos caem na conta dele), você precisa que ele envie **estes 4 itens** da conta Stripe dele:

| # | O que pedir | Variável no Railway | Onde o cliente obtém no Stripe |
|---|--------------|---------------------|---------------------------------|
| 1 | **Chave secreta** (Secret key) | `STRIPE_SECRET` | [Developers → API keys](https://dashboard.stripe.com/apikeys) → revelar **Secret key** (começa com `sk_live_` ou `sk_test_`) |
| 2 | **Chave publicável** (Publishable key) | `STRIPE_KEY` | Mesmo lugar → **Publishable key** (começa com `pk_live_` ou `pk_test_`) |
| 3 | **ID do preço** do plano mensal | `STRIPE_SUBSCRIPTION_PRICE_ID` | [Products](https://dashboard.stripe.com/products) → criar/abrir o produto da assinatura → no preço recorrente (mensal), copiar o **Price ID** (ex.: `price_1ABC...`) |
| 4 | **Segredo do webhook** (Signing secret) | `STRIPE_WEBHOOK_SECRET` | [Developers → Webhooks](https://dashboard.stripe.com/webhooks) → **Add endpoint** → URL: `https://SUA-URL-RAILWAY.up.railway.app/stripe/webhook` → eventos: `checkout.session.completed`, `customer.subscription.updated`, `customer.subscription.deleted`, `invoice.paid`, `invoice.payment_failed` → criar → revelar **Signing secret** (começa com `whsec_`) |

**Importante:** O webhook precisa apontar para a URL **real** do app no Railway (ex.: `https://seu-app.up.railway.app/stripe/webhook`). Só depois do deploy o cliente (ou você) cria o endpoint no Stripe e cola o Signing secret em `STRIPE_WEBHOOK_SECRET`.

---

## Todas as variáveis para o ambiente Railway

Use esta tabela para preencher **Variables** no painel do Railway. Marque o que já configurou.

### Obrigatórias (app + banco)

| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `APP_KEY` | Chave da aplicação Laravel | `base64:...` (gerar com `php artisan key:generate`) |
| `APP_ENV` | Ambiente | `production` |
| `APP_DEBUG` | Debug em produção | `false` |
| `APP_URL` | URL do app no Railway | `https://seu-app.up.railway.app` |
| `DB_CONNECTION` | Driver do banco | `mysql` |
| `DB_HOST` | Host do MySQL (Railway) | (valor do serviço MySQL) |
| `DB_PORT` | Porta do MySQL | (valor do Railway) |
| `DB_DATABASE` | Nome do banco | (valor do Railway) |
| `DB_USERNAME` | Usuário do banco | (valor do Railway) |
| `DB_PASSWORD` | Senha do banco | (valor do Railway) |

### Recomendadas (e-mail e trial)

| Variável | Descrição |
|----------|-----------|
| `ADMIN_EMAIL` | E-mail que recebe notificação de **novo trial** (negócio se registrou) |
| `SENDGRID_API_KEY` ou `RESEND_API_KEY` | Envio de e-mails (trial, avaliações, loja, recuperação de senha) – pelo menos um |
| `MAIL_FROM_ADDRESS` | E-mail remetente dos envios |
| `MAIL_FROM_NAME` | Nome do remetente (ex.: "Avalie e Ganhe") |

### Stripe (assinatura – conta do cliente)

| Variável | Descrição |
|----------|-----------|
| `STRIPE_KEY` | Chave publicável (Publishable) da conta do cliente |
| `STRIPE_SECRET` | Chave secreta (Secret) da conta do cliente |
| `STRIPE_WEBHOOK_SECRET` | Signing secret do webhook (URL do app no Railway) |
| `STRIPE_SUBSCRIPTION_PRICE_ID` | Price ID do plano mensal (ex.: `price_xxx`) |
| `STRIPE_CURRENCY` | (Opcional) Moeda. Padrão: `brl` |

### Opcionais

| Variável | Descrição |
|----------|-----------|
| `APP_PUBLIC_URL` | URL pública exibida aos usuários (ex.: `https://www.seudominio.com`) se diferente de `APP_URL` |
| `CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, `CLOUDINARY_API_SECRET`, `CLOUDINARY_FOLDER` | Armazenamento de imagens (logos, fundos) – persistem após redeploy |
| `STORE_URL` | URL da loja externa (ex.: Shopify), se usar |
| `RESEND_FROM_ADDRESS`, `RESEND_FROM_NAME` | Remetente específico do Resend (quando usar Resend) |

---

## Configuração do projeto no Railway

- **Root do repositório:** use a **raiz do repositório** como diretório do serviço no Railway (não defina "Root Directory" como `reviews-platform`). O `railway.json` na raiz contém o build e o start corretos (migrate, seed, storage:link, serve).
- **Banco de dados:** crie um serviço MySQL no Railway e use as variáveis de conexão fornecidas (ou link "Variables" do MySQL).

---

## O que o start command já faz

O `railway.json` na **raiz** do repositório define um start command que:

1. Cria diretórios necessários em `storage` e `bootstrap/cache`
2. Executa `php artisan migrate --force`
3. Executa `php artisan db:seed --class=AdminUserSeeder --force` (cria admin se não existir)
4. Executa `php artisan storage:link`
5. Inicia o servidor com `php artisan serve --host=0.0.0.0 --port=$PORT`

Não é necessário rodar migrate/seed manualmente no primeiro deploy; o primeiro deploy já faz isso.

---

## Agendador (scheduler) – tarefas automáticas

O Laravel agenda a **sincronização das assinaturas com o Stripe** a cada hora (`billing:sync-all-subscriptions`), para manter o status de cancelamento/ativação correto mesmo se o webhook falhar.

Para o agendador rodar em produção, é preciso executar o scheduler a cada minuto. No Railway (ou em qualquer servidor), configure um **cron**:

```bash
* * * * * cd /caminho/para/reviews-platform && php artisan schedule:run >> /dev/null 2>&1
```

No Railway: use a opção **Cron Job** do serviço (se disponível) ou um serviço separado que rode apenas `php artisan schedule:run` a cada minuto. Sem isso, o agendador não executa e a sincronização horária das assinaturas não ocorre (o webhook do Stripe continua sendo a principal forma de atualização em tempo real).

---

## Migrations incluídas (novas implementações)

As migrations atuais cobrem, entre outras coisas:

- Tabelas de empresas, avaliações, usuários, páginas de avaliação
- **Loja:** `store_products`, `store_requests`, **`store_settings`** (e-mail de notificação da loja)

Nada extra é necessário para as novas implementações além das variáveis acima.

---

## Checklist rápido pós-deploy

- [ ] Acessar `APP_URL` e ver a landing/login
- [ ] Fazer login com o admin criado pelo seed (ver logs do primeiro deploy: e-mail e senha padrão)
- [ ] Enviar um trial de teste e conferir se o `ADMIN_EMAIL` recebeu o e-mail
- [ ] (Opcional) Configurar e-mail de notificação da Loja em **Gerenciar loja** (salvo em `store_settings`)

---

---

## O sistema está pronto para o deploy?

**Sim.** O sistema está pronto para deploy no Railway. Resumo:

1. **Raiz do repositório** como diretório do serviço (não use `reviews-platform` como root).
2. **MySQL** no Railway e variáveis de banco (DB_*) preenchidas.
3. **Variáveis obrigatórias** (APP_*, DB_*) definidas.
4. **Variáveis recomendadas** (ADMIN_EMAIL, SendGrid ou Resend, MAIL_FROM_*) para e-mails e trial.
5. **Stripe:** quando o cliente enviar os 4 dados da conta dele, preencha STRIPE_KEY, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET e STRIPE_SUBSCRIPTION_PRICE_ID no Railway.
6. **Webhook Stripe:** após o primeiro deploy, crie o endpoint no Stripe com a URL `https://SUA-URL-RAILWAY.up.railway.app/stripe/webhook` e adicione o Signing secret em `STRIPE_WEBHOOK_SECRET`.

**Resumo:** Configure as variáveis listadas acima no painel do Railway → Variables e faça o deploy. O restante (migrate, seed, storage) é executado automaticamente no start.
