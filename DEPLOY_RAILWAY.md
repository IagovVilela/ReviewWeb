# Deploy no Railway

## Status: pronto para deploy

O projeto já está configurado para rodar no Railway:

- **Migrações** rodam automaticamente no start (`php artisan migrate --force`)
- **Storage** (pastas e permissões) é criado no start
- **Build** usa Nixpacks (PHP 82, Composer, Node 18)

---

## O que você precisa fazer

### 1. Fazer commit e push (se ainda não fez)

No terminal, na pasta do projeto:

```bash
git add .
git commit -m "Preparar deploy: migração created_by e config Railway"
git push origin main
```

(Substitua `main` pelo nome da sua branch se for outra.)

### 2. Deploy no Railway

- Se o Railway está **ligado ao GitHub**: o deploy começa sozinho após o push.
- Se você faz deploy **manual**: no painel do Railway, abra o projeto e clique em **Deploy** (ou **Redeploy**).

Nada mais é necessário; as migrações rodam no start.

---

## Variáveis de ambiente no Railway

Confira no Railway (Settings → Variables) se existem:

| Variável | Uso |
|----------|-----|
| `APP_KEY` | Chave da aplicação Laravel |
| `APP_URL` | Ex: `https://www.avalieganhe.app` |
| `APP_ENV` | `production` |
| `DB_*` ou `DATABASE_URL` | Banco de dados |
| `SENDGRID_API_KEY` | Envio de e-mail |
| `MAIL_FROM_ADDRESS` | Ex: `no-reply@avalieganhe.app` |
| `MAIL_FROM_NAME` | Ex: `Avalie e Ganhe` |
| **`SESSION_DRIVER`** | **Use `database`** no Railway (obrigatório para evitar 401 ao adicionar usuário à empresa; ver abaixo) |
| **`SESSION_DOMAIN`** | Opcional. Use `.avalieganhe.app` (com ponto na frente) para o cookie valer em todo o domínio |

---

## Erro 401 ao adicionar usuário à empresa

Se ao clicar em "Adicionar usuário" na edição da empresa aparecer **401 Unauthenticated**, a sessão não está sendo reconhecida. No Railway isso costuma acontecer quando:

1. **Sessão em arquivo** – Com mais de uma instância ou reinício do container, a sessão em arquivo se perde.
2. **Cookie de sessão** – Domínio ou caminho do cookie incorreto.

**O que fazer:**

1. **Usar sessão em banco**
   - No Railway, em **Variables**, adicione:
     - `SESSION_DRIVER=database`
   - A migração que cria a tabela `sessions` já existe e roda com `php artisan migrate --force`. Não é preciso comando extra.

2. **Domínio do cookie (opcional)**
   - Se o site for acessado por `www.avalieganhe.app`, adicione:
     - `SESSION_DOMAIN=.avalieganhe.app`
   - O ponto na frente faz o cookie valer para `www.avalieganhe.app` e `avalieganhe.app`.

3. **Redeploy**
   - Depois de alterar as variáveis, faça um novo deploy (ou reinicie o serviço) e teste de novo o "Adicionar usuário".

---

## Logs para diagnosticar 401 (adicionar usuário à empresa)

Quando o 401 continuar acontecendo, o backend passa a gravar logs detalhados. Use-os para entender a causa:

### Onde ver os logs no Railway

- No painel do Railway: abra o serviço → **Deployments** → clique no deploy ativo → aba **Logs** (ou **View Logs**).
- Ou no **Shell** do serviço, se tiver: `cat storage/logs/laravel.log` (caminho pode variar).

### O que aparece nos logs

1. **Antes do 401** – linha `Auth diagnostics for members API`:
   - `session_id` / `session_id_short`: ID da sessão carregada.
   - `has_session_cookie`: se o request trouxe o cookie de sessão.
   - `auth_check`: se o Laravel considerou o usuário autenticado.
   - `session_driver`: driver configurado (deve ser `database` em produção).

2. **No 401** – linha `401 Unauthenticated on members API`:
   - `has_session`, `has_session_cookie`: ajudam a ver se a sessão chegou ao servidor.
   - `session_driver`: confirma se está usando `database`.

### Como interpretar

- **`auth_check: false` e `has_session_cookie: true`**  
  Cookie chegou mas a sessão não foi reconhecida (ex.: sessão em outro container ou driver `file` com mais de uma instância). Solução: `SESSION_DRIVER=database` e redeploy.

- **`has_session_cookie: false`**  
  O navegador não está enviando o cookie (domínio/path, HTTPS, SameSite). Verifique `SESSION_DOMAIN` e se o site é acessado por HTTPS.

- **`Unexpected token 'export'` no navegador**  
  Isso costuma ser o front tentando interpretar a resposta 401 como JSON quando o servidor devolve HTML. Com as alterações atuais, o 401 em rotas `/api/*` passa a ser sempre JSON; no front, a resposta é tratada sem chamar `.json()` em HTML, evitando esse erro.

---

## Resumo

1. **Commit + push** (ou só push se já commitou).
2. **Deploy** (automático pelo GitHub ou manual no Railway).

Depois do deploy, a nova migração `created_by` será aplicada sozinha.
