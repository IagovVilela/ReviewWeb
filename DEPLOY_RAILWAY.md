# Deploy no Railway

## Status: pronto para deploy

O projeto já está configurado para rodar no Railway:

- **Start command** (em `railway.json` e `nixpacks.toml`): a cada deploy o container sobe com criação de pastas de storage, **migrações** (`php artisan migrate --force`), `storage:link` e depois o servidor. Tudo automático, sem precisar de Shell.
- **Fallback de sessão**: se `SESSION_DRIVER=database` estiver ativo mas a tabela `sessions` ainda não existir, o app usa temporariamente o driver `file` para evitar 500 (a página carrega; ao fazer um novo deploy, as migrações criam a tabela e o driver em banco passa a valer).
- **Storage** (pastas e permissões) é criado no start.
- **Build** usa Nixpacks (PHP 82, Composer, Node 18).

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

## HTTP 500 em /companies (ou “Esta página não está funcionando”)

Se ao acessar **/companies** (ou qualquer página após o login) aparecer **HTTP ERROR 500**, a causa mais comum é:

**`SESSION_DRIVER=database` está ativo, mas a tabela `sessions` não existe no banco.**

O Laravel tenta usar o driver de sessão em banco; se a tabela não existir, qualquer página que use sessão (incluindo /companies) quebra com 500.

**O que fazer:**

1. **Migrações no deploy**
   - O comando de start do Railway (`railway.json`) já roda **`php artisan migrate --force`** antes de subir o servidor. Assim a tabela `sessions` (e as demais) é criada/atualizada a cada deploy. Não é preciso rodar nada manualmente.

2. **Conferir a tabela `sessions`**
   - No banco usado pelo Railway (MySQL/Postgres), verifique se existe a tabela `sessions`. Se não existir, faça um novo deploy (o start com migrate vai criá-la).

3. **Ver o erro real na tela (diagnóstico rápido)**
   - No Railway, em **Variables**, adicione temporariamente: **`APP_EXPOSE_500_MESSAGE=1`**.
   - Faça **redeploy**.
   - Acesse **https://www.avalieganhe.app/companies-debug** (faça login antes se precisar). O app devolve o erro com status 200, então o proxy não mostra a página genérica — o corpo da resposta mostra a exceção e o trace.
   - Se ainda aparecer a página genérica de 500, tente acessar de novo **/companies** — com a variável ativa, qualquer exceção passa a ser devolvida com status 200 para o corpo não ser trocado.
   - Copie a mensagem de erro, **remova** a variável `APP_EXPOSE_500_MESSAGE` e faça um novo deploy (não deixe essa variável em produção).

4. **Ver o erro nos logs**
   - Após dar 500, abra **Deployments** → deploy ativo → **Logs** e procure por **`LARAVEL_500`**. A linha seguinte mostra a exceção. Copie essa mensagem para corrigir ou enviar a quem for debugar.

**Alternativa temporária:** se não puder rodar migrações agora, volte o driver de sessão para arquivo no Railway: `SESSION_DRIVER=file`. O 401 ao adicionar usuário à empresa pode voltar, mas o 500 some. Depois que a tabela `sessions` existir, use de novo `SESSION_DRIVER=database`.

---

## Logs para diagnosticar 401 (adicionar usuário à empresa)

Quando o 401 continuar acontecendo, o backend passa a gravar logs detalhados. Use-os para entender a causa:

### Onde ver os logs no Railway

- No painel do Railway: abra o serviço → **Deployments** → clique no deploy ativo → aba **Logs** (ou **View Logs**).
- Os logs do container aparecem na aba **Logs** do deploy; não é necessário Shell.

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
