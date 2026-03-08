# 📧 06 - Sistema de Email

Documentação do sistema de email SMTP.

## 📁 Documentos

- **CONFIGURAR_EMAIL_SMTP.md** - ⭐ Guia completo de configuração (LEIA PRIMEIRO)
- **EMAIL_SETUP.md** - Setup de email

## 🚀 Configuração Rápida

1. **Leia:** `CONFIGURAR_EMAIL_SMTP.md`
2. **Configure:** Variáveis no `.env`
3. **Teste:** Envie email de teste
4. **Verifique:** Logs em `storage/logs/`

## ⚙️ Variáveis Necessárias

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔧 Suporte a Provedores

- **Gmail** (configurado por padrão)
- **Outlook/Hotmail**
- **Yahoo**
- **SMTP customizado**

## 🐛 Problemas Comuns

- **Erro de autenticação:** Veja senha de app em `CONFIGURAR_EMAIL_SMTP.md`
- **Erro de conexão:** Verifique firewall/proxy
- **Email não enviado:** Verifique logs em `storage/logs/`
- **Logo não aparece nos emails:** Use URLs absolutas para imagens no conteúdo do email.

## 📞 Mais Ajuda

Consulte seção Troubleshooting em `CONFIGURAR_EMAIL_SMTP.md`

