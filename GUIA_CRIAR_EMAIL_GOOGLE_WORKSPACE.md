# 📧 Guia Completo: Criar no-reply@avalieganhe.app no Google Workspace

## 🎯 Objetivo
Criar o email `no-reply@avalieganhe.app` usando Google Workspace e configurar no sistema.

---

## 📋 Passo a Passo Completo

### **PASSO 1: Criar Conta no Google Workspace**

1. **Acesse:** https://workspace.google.com
2. Clique em **"Começar"** ou **"Get Started"**
3. Preencha:
   - **Nome da empresa:** Avalie e Ganhe (ou nome que preferir)
   - **Número de funcionários:** Escolha a opção
   - **Nome:** Seu nome
   - **Email atual:** Seu email pessoal (para contato)
4. Clique em **"Próximo"**

---

### **PASSO 2: Verificar Domínio**

1. **Digite seu domínio:** `avalieganhe.app`
2. Clique em **"Próximo"**
3. O Google pedirá para verificar que você é o dono do domínio

**Opções de Verificação:**

**Opção A: Adicionar Registro TXT no DNS (Recomendado)**
- O Google fornecerá um registro TXT
- Exemplo: `google-site-verification=abc123xyz...`
- Adicione no painel DNS da LCN:
  - **Tipo:** TXT
  - **Host name:** Deixe vazio ou `@`
  - **Result:** (valor fornecido pelo Google)
- Aguarde alguns minutos
- Volte ao Google e clique em **"Verificar"**

**Opção B: Upload de Arquivo HTML**
- Baixe o arquivo HTML fornecido
- Faça upload no servidor (se tiver acesso)
- Mais complexo, não recomendado

---

### **PASSO 3: Escolher Plano**

O Google Workspace oferece planos:

**Business Starter:**
- R$ ~25/mês por usuário
- 30 GB de armazenamento
- Email profissional
- ✅ **Recomendado para começar**

**Business Standard:**
- R$ ~50/mês por usuário
- 2 TB de armazenamento
- Mais recursos

**Escolha o Business Starter** e clique em **"Próximo"**

---

### **PASSO 4: Criar Conta de Administrador**

1. **Crie o primeiro usuário:**
   - **Nome:** Seu nome
   - **Email:** `admin@avalieganhe.app` (ou outro que preferir)
   - **Senha:** Crie uma senha forte
2. Clique em **"Próximo"**

**⚠️ IMPORTANTE:** Anote essas credenciais! Você vai precisar delas.

---

### **PASSO 5: Configurar Pagamento**

1. Preencha dados de pagamento
2. O Google oferece **14 dias grátis** para testar
3. Após 14 dias, começa a cobrança
4. Complete o cadastro

---

### **PASSO 6: Configurar DNS para Email**

O Google fornecerá registros DNS que você precisa adicionar no LCN:

#### **Registros Necessários:**

**1. Registro MX (Mail Exchange):**
```
Tipo: MX
Prioridade: 1
Host name: @ (ou deixe vazio)
Valor: aspmx.l.google.com
```

**2. Mais registros MX (Google fornece vários):**
```
Tipo: MX | Prioridade: 5 | Host: @ | Valor: alt1.aspmx.l.google.com
Tipo: MX | Prioridade: 5 | Host: @ | Valor: alt2.aspmx.l.google.com
Tipo: MX | Prioridade: 10 | Host: @ | Valor: alt3.aspmx.l.google.com
Tipo: MX | Prioridade: 10 | Host: @ | Valor: alt4.aspmx.l.google.com
```

**3. Registro TXT para SPF:**
```
Tipo: TXT
Host name: @ (ou deixe vazio)
Valor: v=spf1 include:_spf.google.com ~all
```

**4. Registro CNAME para DKIM (opcional, mas recomendado):**
O Google fornecerá valores específicos após criar o domínio.

**Como Adicionar no LCN:**
1. Acesse o painel DNS da LCN
2. Adicione cada registro MX (comece pela prioridade 1)
3. Adicione o registro TXT para SPF
4. Aguarde propagação (pode levar algumas horas)

---

### **PASSO 7: Criar Email no-reply@avalieganhe.app**

1. **Acesse o Admin Console do Google:**
   - https://admin.google.com
   - Faça login com `admin@avalieganhe.app` (ou o email que criou)

2. **Vá em Usuários:**
   - Menu lateral → **"Usuários"** ou **"Users"**

3. **Criar Novo Usuário:**
   - Clique em **"Adicionar novo usuário"** ou **"Add new user"**
   - **Nome:** No Reply
   - **Email:** `no-reply@avalieganhe.app`
   - **Senha:** Crie uma senha forte (você não vai usar, mas precisa)
   - **Desmarque:** "Exigir que o usuário altere a senha no próximo login"
   - Clique em **"Adicionar"** ou **"Add"**

4. **⚠️ IMPORTANTE:** Anote a senha criada! Você vai precisar para configurar SMTP.

---

### **PASSO 8: Configurar SMTP no Projeto**

Agora configure o Laravel para usar o email do Google Workspace.

#### **8.1. Obter Senha de App do Google**

1. **Acesse:** https://myaccount.google.com/security
2. Faça login com `no-reply@avalieganhe.app`
3. Ative **"Verificação em duas etapas"** (obrigatório)
4. Acesse: https://myaccount.google.com/apppasswords
5. Selecione:
   - **App:** Mail
   - **Device:** Other (Custom name)
   - Digite: "Laravel Reviews Platform"
6. Clique em **"Gerar"**
7. **COPIE a senha de 16 caracteres** (ex: `abcd efgh ijkl mnop`)

#### **8.2. Configurar .env**

Edite o arquivo `reviews-platform/.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@avalieganhe.app
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@avalieganhe.app
MAIL_FROM_NAME="Avalie e Ganhe"
```

**⚠️ IMPORTANTE:** 
- Use a senha de app gerada (não a senha normal)
- Remova espaços da senha: `abcd efgh ijkl mnop` → `abcdefghijklmnop`

#### **8.3. Limpar Cache**

```bash
cd reviews-platform
php artisan config:clear
php artisan cache:clear
```

---

### **PASSO 9: Testar**

1. **Envie uma nova avaliação** no sistema
2. **Verifique se o email chegou** na caixa de entrada
3. **Verifique o remetente:** deve aparecer `no-reply@avalieganhe.app`

---

## ✅ Checklist Completo

- [ ] Criou conta no Google Workspace
- [ ] Verificou domínio `avalieganhe.app`
- [ ] Escolheu plano (Business Starter)
- [ ] Criou conta de administrador
- [ ] Configurou pagamento (14 dias grátis)
- [ ] Adicionou registros MX no DNS da LCN
- [ ] Adicionou registro TXT SPF no DNS
- [ ] Aguardou propagação DNS (algumas horas)
- [ ] Criou usuário `no-reply@avalieganhe.app` no Google
- [ ] Ativou verificação em duas etapas
- [ ] Gerou senha de app
- [ ] Configurou `.env` com credenciais
- [ ] Limpou cache do Laravel
- [ ] Testou envio de email
- [ ] Verificou se email chegou

---

## 💰 Custos

**Google Workspace Business Starter:**
- **R$ ~25/mês** por usuário
- **14 dias grátis** para testar
- Se criar apenas `no-reply@avalieganhe.app`, será 1 usuário = R$ 25/mês

**Alternativa mais barata:**
- Zoho Mail: **Gratuito** até 5 usuários
- Veja guia alternativo se preferir

---

## 🚨 Problemas Comuns

### **Problema 1: DNS não propagou**

**Solução:**
- Aguarde mais tempo (pode levar até 48 horas)
- Verifique se copiou os valores corretamente
- Use ferramenta: https://mxtoolbox.com/ para verificar MX

### **Problema 2: Erro de autenticação**

**Solução:**
- Certifique-se de usar senha de app (não senha normal)
- Verifique se ativou verificação em duas etapas
- Remova espaços da senha no .env

### **Problema 3: Email não chega**

**Solução:**
- Verifique se registros MX estão corretos
- Verifique se SPF está configurado
- Aguarde propagação DNS completa

---

## 💡 Dica Importante

**Após criar o email no Google Workspace:**
- Você pode acessar o Gmail com `no-reply@avalieganhe.app`
- Mas como é "no-reply", não precisa acessar
- Use apenas para envio de emails do sistema

---

## 🎯 Resumo Rápido

1. **Criar conta Google Workspace** → Verificar domínio
2. **Configurar DNS** → Adicionar MX e SPF no LCN
3. **Criar usuário** → `no-reply@avalieganhe.app`
4. **Gerar senha de app** → Para SMTP
5. **Configurar .env** → Com credenciais
6. **Testar** → Enviar email

---

**Precisa de ajuda em algum passo específico? Me avise!**

---

**Versão:** 1.0  
**Data:** 2025  
**Domínio:** avalieganhe.app



