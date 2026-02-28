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

---

## Resumo

1. **Commit + push** (ou só push se já commitou).
2. **Deploy** (automático pelo GitHub ou manual no Railway).

Depois do deploy, a nova migração `created_by` será aplicada sozinha.
