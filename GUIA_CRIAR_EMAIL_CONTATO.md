# 📧 Guia: Criar Email de Contato para Junior

## 🎯 O que o Cliente Quer

O cliente precisa de **2 emails**:

1. **Email de Contato** (para Junior e dia a dia):
   - Exemplo: `contato@avalieganhe.app` ou `junior@avalieganhe.app`
   - **Precisa ser email REAL** (com caixa de entrada para receber emails)
   - Para receber emails de clientes, parceiros, etc.

2. **Email no-reply** (já configurado ✅):
   - `no-reply@avalieganhe.app`
   - Só para envio automático de avaliações
   - Não precisa de caixa de entrada

---

## 📋 Opções para Criar Email de Contato

### **OPÇÃO 1: Google Workspace** (Recomendado - Profissional)

**Custo:** R$ ~25/mês por usuário

**Vantagens:**
- ✅ Email profissional real
- ✅ Interface Gmail familiar
- ✅ Melhor reputação
- ✅ 30 GB de armazenamento

**Como fazer:**
1. Acesse: https://workspace.google.com
2. Crie conta e verifique domínio `avalieganhe.app`
3. Crie usuário: `contato@avalieganhe.app` ou `junior@avalieganhe.app`
4. Configure DNS (MX records) no LCN
5. Pronto! Email funcionando

**Guia completo:** `GUIA_CRIAR_EMAIL_GOOGLE_WORKSPACE.md`

---

### **OPÇÃO 2: Zoho Mail** (Mais Barato - Gratuito até 5 usuários)

**Custo:** Gratuito (até 5 usuários) ou R$ 5/mês

**Vantagens:**
- ✅ Gratuito para começar
- ✅ Email profissional real
- ✅ Interface similar ao Gmail

**Como fazer:**
1. Acesse: https://www.zoho.com/mail/
2. Crie conta gratuita
3. Verifique domínio `avalieganhe.app`
4. Crie usuário: `contato@avalieganhe.app`
5. Configure DNS (MX records) no LCN
6. Pronto! Email funcionando

---

### **OPÇÃO 3: LCN (Se Oferecer Email)**

**Custo:** Depende do plano LCN

**Vantagens:**
- ✅ Tudo em um lugar (domínio + email)
- ✅ Pode ser mais barato

**Como fazer:**
1. Acesse painel LCN
2. Procure por "Email", "Mailboxes" ou "Contas de Email"
3. Crie conta: `contato@avalieganhe.app`
4. Configure senha
5. Use credenciais SMTP do LCN (se necessário)

---

### **OPÇÃO 4: Email Temporário (Só para Testes)**

**Custo:** Gratuito

**Como fazer:**
1. Use um email temporário ou redirecionamento
2. Configure `contato@avalieganhe.app` para redirecionar para `iagovventura@gmail.com`
3. **Limitação:** Não é ideal para produção

---

## 🎯 RECOMENDAÇÃO

### **Para Produção (Recomendado):**

**Zoho Mail (Gratuito):**
- ✅ Gratuito até 5 usuários
- ✅ Email profissional real
- ✅ Fácil de configurar
- ✅ Interface familiar

**Ou Google Workspace:**
- ✅ Mais profissional
- ✅ Melhor integração
- ⚠️ Custo: R$ 25/mês

---

## 📋 Passo a Passo: Criar com Zoho Mail (Gratuito)

### **PASSO 1: Criar Conta no Zoho**

1. Acesse: https://www.zoho.com/mail/
2. Clique em **"Sign Up Now"** ou **"Get Started"**
3. Escolha **"Mail"** → **"Free Plan"**
4. Preencha:
   - Nome da empresa: Avalie e Ganhe
   - Seu nome
   - Email pessoal (para contato)
5. Clique em **"Sign Up"**

---

### **PASSO 2: Verificar Domínio**

1. No painel do Zoho, clique em **"Add Domain"**
2. Digite: `avalieganhe.app`
3. Escolha método de verificação: **"TXT Record"**
4. O Zoho fornecerá um registro TXT
5. Adicione no painel DNS da LCN:
   - **Tipo:** TXT
   - **Host name:** (vazio ou @)
   - **Valor:** (valor fornecido pelo Zoho)
6. Aguarde alguns minutos
7. Volte ao Zoho e clique em **"Verify"**

---

### **PASSO 3: Configurar DNS para Email (MX Records)**

O Zoho fornecerá registros MX que você precisa adicionar no LCN:

**Exemplo de registros MX do Zoho:**
```
Tipo: MX | Prioridade: 10 | Host: @ | Valor: mx.zoho.com
Tipo: MX | Prioridade: 20 | Host: @ | Valor: mx2.zoho.com
```

**Como adicionar no LCN:**
1. Acesse painel DNS da LCN
2. Adicione cada registro MX:
   - **Host name:** (vazio ou @)
   - **Type:** MX
   - **Prioridade:** (valor fornecido pelo Zoho)
   - **Result:** (valor fornecido pelo Zoho)
3. Salve
4. Aguarde propagação DNS (algumas horas)

---

### **PASSO 4: Criar Email contato@avalieganhe.app**

1. No painel do Zoho, vá em **"Users"** ou **"Mailboxes"**
2. Clique em **"Add User"** ou **"Create Mailbox"**
3. Preencha:
   - **Nome:** Contato Avalie e Ganhe
   - **Email:** `contato@avalieganhe.app`
   - **Senha:** Crie uma senha forte
4. Clique em **"Create"**

**Ou criar `junior@avalieganhe.app`:**
- **Nome:** Junior
- **Email:** `junior@avalieganhe.app`
- **Senha:** Crie uma senha forte

---

### **PASSO 5: Acessar Email**

1. Acesse: https://mail.zoho.com
2. Faça login com: `contato@avalieganhe.app` (ou `junior@avalieganhe.app`)
3. Pronto! Email funcionando

---

## 📋 Resumo: O que o Cliente Quer

| Email | Uso | Tipo | Status |
|-------|-----|------|--------|
| `no-reply@avalieganhe.app` | Envio automático de avaliações | Apenas envio | ✅ **Já configurado** |
| `contato@avalieganhe.app` ou `junior@avalieganhe.app` | Receber emails de clientes/parceiros | Email real (recebe + envia) | ⚠️ **Precisa criar** |

---

## 💡 Qual Email Criar?

**Opções:**
- `contato@avalieganhe.app` - Mais genérico, para toda empresa
- `junior@avalieganhe.app` - Específico para o Junior
- `atendimento@avalieganhe.app` - Focado em atendimento

**Recomendação:** `contato@avalieganhe.app` (mais profissional e genérico)

---

## ✅ Checklist

- [ ] Escolhi qual email criar (`contato@` ou `junior@`)
- [ ] Escolhi provedor (Zoho Mail gratuito ou Google Workspace)
- [ ] Criei conta no provedor escolhido
- [ ] Verifiquei domínio `avalieganhe.app`
- [ ] Adicionei registros MX no DNS da LCN
- [ ] Aguardei propagação DNS
- [ ] Criei email `contato@avalieganhe.app` (ou `junior@`)
- [ ] Testei acessando o email
- [ ] Testei recebendo um email de teste

---

## 🚀 Próximos Passos

1. **Decidir qual email criar:**
   - `contato@avalieganhe.app` (recomendado)
   - `junior@avalieganhe.app`
   - Outro?

2. **Escolher provedor:**
   - Zoho Mail (gratuito) - Recomendado
   - Google Workspace (R$ 25/mês) - Mais profissional

3. **Criar e configurar:**
   - Seguir passo a passo acima

---

**Me diga qual email você quer criar e qual provedor prefere, e eu te ajudo a configurar!** 🚀


