# 🌐 Guia Completo: Configurar DNS no Painel LCN

> **Guia para iniciantes em DNS e redes**

## 📚 O que é DNS? (Explicação Simples)

DNS (Domain Name System) é como um "catálogo telefônico" da internet. Quando você digita `avalieganhe.app` no navegador, o DNS diz ao computador: "Esse domínio está hospedado no servidor X".

## 🎯 O que você precisa fazer

Apontar o domínio `avalieganhe.app` para o servidor do Railway onde sua aplicação está rodando.

---

## 📋 PASSO 1: Descobrir o que o Railway precisa

### ⚠️ IMPORTANTE: Railway geralmente NÃO fornece IP estático!

O Railway **não fornece um IP fixo** para domínios personalizados. Eles usam **CNAME** (apontamento para domínio). Isso significa que você **não pode usar registro A** diretamente com um IP do Railway.

### Opção A: Railway fornece CNAME (99% dos casos)

1. Acesse o painel do Railway: https://railway.app
2. Vá no seu projeto → Serviço da aplicação
3. Clique em **"Settings"** → **"Networking"** ou **"Domains"**
4. Clique em **"+ Custom Domain"** ou **"Add Domain"**
5. Digite: `avalieganhe.app` ou `www.avalieganhe.app`
6. O Railway mostrará:
   - **Tipo:** `CNAME`
   - **Valor:** algo como `v90jqdh1.up.railway.app`
   - ✅ **Este é o valor que você precisa usar!**

### Opção B: Como descobrir o IP atual do Railway (se realmente precisar)

**⚠️ ATENÇÃO:** Este IP pode mudar a qualquer momento! Não é recomendado usar.

Se você **realmente** precisa de um IP (por exemplo, seu provedor DNS não aceita CNAME no domínio raiz), você pode descobrir o IP atual assim:

#### Método 1: Usando ferramenta online
1. Acesse: https://dnschecker.org
2. Digite: `v90jqdh1.up.railway.app` (o domínio do Railway)
3. Escolha tipo: `A`
4. Veja o IP que aparece (ex: `85.233.160.22`)
5. ⚠️ **Este IP pode mudar!** Use apenas se necessário

#### Método 2: Usando linha de comando (se tiver acesso)
```bash
nslookup v90jqdh1.up.railway.app
```
ou
```bash
ping v90jqdh1.up.railway.app
```

**⚠️ PROBLEMA:** Se você usar este IP em um registro A, ele pode mudar e seu site parar de funcionar!

### ✅ SOLUÇÃO RECOMENDADA: Usar apenas www com CNAME

A melhor solução é:
1. Configurar `www.avalieganhe.app` com CNAME apontando para `v90jqdh1.up.railway.app`
2. Redirecionar `avalieganhe.app` (domínio raiz) para `www.avalieganhe.app`

Assim você não precisa de IP e tudo funciona perfeitamente!

---

## 📋 PASSO 2: Entender os tipos de registros DNS

### 🔵 Registro A
- **O que faz:** Aponta diretamente para um endereço IP
- **Quando usar:** Para o domínio raiz (`avalieganhe.app`)
- **Exemplo:**
  - Host name: (vazio ou `@`)
  - Type: `A`
  - Result: `85.233.160.22`

### 🟢 Registro CNAME
- **O que faz:** Aponta para outro domínio (como um "atalho")
- **Quando usar:** Para subdomínios (como `www.avalieganhe.app`)
- **⚠️ IMPORTANTE:** CNAME **NÃO pode** ser usado no domínio raiz!
- **Exemplo:**
  - Host name: `www`
  - Type: `CNAME`
  - Result: `v90jqdh1.up.railway.app`

### 🟡 Registro TXT
- **O que faz:** Armazena informações de texto (usado para email, verificação, etc.)
- **Quando usar:** Para configurações de email (SPF, DKIM)
- **Exemplo:**
  - Host name: (vazio ou `@`)
  - Type: `TXT`
  - Result: `v=spf1 include:sendgrid.net ~all`

---

## 📋 PASSO 3: Configurar no Painel LCN

### ✅ SOLUÇÃO RECOMENDADA: Configurar www com CNAME + Redirecionar raiz

Esta é a **melhor e mais segura** forma de configurar:

#### **Registro 1: Para www (CNAME) - OBRIGATÓRIO**

1. Clique em **"+ Add records"**
2. Preencha:
   - **Host name:** `www` (digite apenas "www", sem aspas)
   - **Type:** `CNAME`
   - **Result:** `v90jqdh1.up.railway.app` (o valor que o Railway forneceu)
3. Clique em **Salvar**

✅ **Este registro já está correto na sua configuração!**

#### **Registro 2: Redirecionar domínio raiz para www - RECOMENDADO**

Como CNAME não funciona no domínio raiz, a melhor solução é redirecionar:

1. No painel LCN, procure por opção de **"Redirect"**, **"Web Forward"** ou **"Redirecionamento"**
2. Configure:
   - **De:** `avalieganhe.app` (domínio raiz, sem www)
   - **Para:** `www.avalieganhe.app`
   - **Tipo:** Redirecionamento permanente (301)
3. Salve

**Resultado:** Quando alguém acessar `avalieganhe.app`, será redirecionado automaticamente para `www.avalieganhe.app` que está apontando para o Railway! ✅

---

### ⚠️ ALTERNATIVA: Usar registro A com IP (NÃO RECOMENDADO)

**⚠️ ATENÇÃO:** Esta opção NÃO é recomendada porque:
- O IP do Railway pode mudar a qualquer momento
- Se o IP mudar, seu site para de funcionar
- Você precisará atualizar manualmente

**Use apenas se:**
- Seu provedor DNS não oferece redirecionamento
- Você não se importa em monitorar e atualizar o IP

#### **Como descobrir o IP atual:**

1. Acesse: https://dnschecker.org
2. Digite: `v90jqdh1.up.railway.app`
3. Escolha tipo: `A`
4. Anote o IP que aparece (ex: `85.233.160.22`)

#### **Configurar registro A:**

1. Clique em **"+ Add records"**
2. Preencha:
   - **Host name:** Deixe **VAZIO** (ou tente `@`)
   - **Type:** `A`
   - **Result:** `85.233.160.22` (o IP que você descobriu)
3. Clique em **Salvar**

**⚠️ LEMBRE-SE:** Se este IP mudar no Railway, você precisará atualizar manualmente!

---

### 🎯 Cenário 2: Railway forneceu um IP (Registro A)

#### **Registro 1: Para domínio raiz (A)**
1. Clique em **"+ Add records"**
2. Preencha:
   - **Host name:** Deixe **VAZIO** (ou tente `@`)
   - **Type:** `A`
   - **Result:** `85.233.160.22` (o IP que o Railway forneceu)
3. Clique em **Salvar**

#### **Registro 2: Para www (A ou CNAME)**

**Opção A - Se tiver IP para www:**
- **Host name:** `www`
- **Type:** `A`
- **Result:** `85.233.160.22` (mesmo IP)

**Opção B - Se o Railway permitir CNAME para www:**
- **Host name:** `www`
- **Type:** `CNAME`
- **Result:** `v90jqdh1.up.railway.app`

---

## 📋 PASSO 4: Configurar registros de email (se necessário)

Se você vai usar email com o domínio (ex: `noreply@avalieganhe.app`), precisa adicionar registros TXT:

### **Registro TXT para SPF (SendGrid)**

1. Clique em **"+ Add records"**
2. Preencha:
   - **Host name:** Deixe **VAZIO** (ou `@`)
   - **Type:** `TXT`
   - **Result:** `v=spf1 include:sendgrid.net ~all`
3. Clique em **Salvar**

**⚠️ Se já existir um registro TXT SPF:**
- Edite o existente e adicione `include:sendgrid.net` ao valor
- Ou crie um novo (alguns sistemas permitem múltiplos TXT)

---

## ✅ PASSO 4.5: Verificar sua configuração atual

Baseado no que você mostrou, você tem:

### ✅ **Registro 1: www (CNAME) - CORRETO!**
- Host name: `www`
- Type: `CNAME`
- Result: `v90jqdh1.up.railway.app`
- ✅ **Está perfeito! Não mexa!**

### ✅ **Registro 2: TXT (SPF) - CORRETO!**
- Host name: (vazio)
- Type: `TXT`
- Result: `v=spf1 include:fwd.hosts.co.uk -all`
- ✅ **Está perfeito! Não mexa!**

### ❌ **Registro 3: A Record - ERRADO!**
- Host name: (vazio)
- Type: `A`
- Result: `v90jqdh1.up.railway.app` ← **ERRO! Deve ser IP, não domínio!**

**O que fazer:**
1. **DELETE este registro A** (o que está com erro)
2. Configure redirecionamento do domínio raiz para www (veja PASSO 3)
3. Ou descubra o IP atual e configure corretamente (veja PASSO 3 - Alternativa)

---

## 🔍 PASSO 5: Verificar se está funcionando

### **Aguardar propagação DNS**

Após configurar, aguarde:
- **Mínimo:** 5-10 minutos
- **Normal:** 30 minutos a 2 horas
- **Máximo:** 24-48 horas (raro)

### **Testar no navegador**

1. Abra uma aba anônima/privada
2. Acesse: `https://avalieganhe.app`
3. Se funcionar, você verá sua aplicação! ✅

### **Verificar DNS online**

Use ferramentas online para verificar:

1. **DNS Checker:** https://dnschecker.org
   - Digite: `avalieganhe.app`
   - Escolha tipo: `A` ou `CNAME`
   - Veja se os valores estão corretos

2. **What's My DNS:** https://www.whatsmydns.net
   - Digite: `avalieganhe.app`
   - Veja a propagação em tempo real

---

## 🚨 Problemas Comuns e Soluções

### **Problema 1: "Host name for CNAME record is invalid"**

**Causa:** Tentou usar CNAME no domínio raiz (campo vazio ou `@`)

**Solução:**
- CNAME só funciona com subdomínios
- Use `www` no hostname para CNAME
- Ou use registro A para o domínio raiz

### **Problema 2: Painel não aceita campo vazio no hostname**

**Soluções a tentar:**
1. Use `@` (será convertido para vazio)
2. Use apenas um ponto: `.`
3. Use o domínio completo: `avalieganhe.app`
4. Entre em contato com suporte LCN

### **Problema 3: Domínio não carrega após configurar**

**Verificações:**
1. ✅ Aguardou tempo suficiente? (pode levar horas)
2. ✅ Configurou o domínio no Railway também?
3. ✅ Os valores estão corretos? (sem espaços extras)
4. ✅ O Railway está rodando? (verifique no painel)

### **Problema 4: www funciona, mas domínio raiz não**

**Causa:** Configurou apenas CNAME para www, mas não configurou o domínio raiz

**Solução:**
- Configure um registro A para o domínio raiz
- Ou configure redirecionamento de `avalieganhe.app` para `www.avalieganhe.app`

---

## ✅ Checklist Final

Antes de considerar concluído, verifique:

- [ ] Descobri o que o Railway precisa (CNAME ou IP)
- [ ] Configurei registro A para domínio raiz (se necessário)
- [ ] Configurei CNAME para www (se necessário)
- [ ] Configurei registros TXT para email (se necessário)
- [ ] Aguardei propagação DNS (mínimo 10 minutos)
- [ ] Testei acessando `https://avalieganhe.app`
- [ ] Verifiquei DNS online (dnschecker.org)
- [ ] Domínio carrega corretamente ✅

---

## 📞 Precisa de Ajuda?

### **Suporte LCN:**
- Email: suporte@lcn.com.br
- Telefone: Verifique no site da LCN
- Chat: Disponível no painel

### **Suporte Railway:**
- Documentação: https://docs.railway.app
- Discord: https://discord.gg/railway
- Email: support@railway.app

---

## 💡 Dicas Importantes

1. **Sempre aguarde propagação DNS** - pode levar tempo!
2. **Anote todos os valores** - guarde em um arquivo de texto
3. **Teste em aba anônima** - evita cache do navegador
4. **Um erro de digitação quebra tudo** - verifique espaços extras
5. **CNAME não funciona no domínio raiz** - sempre use subdomínio ou registro A

---

## 🎯 Resumo Rápido

**Para domínio raiz (`avalieganhe.app`):**
- Use registro **A** com IP (se Railway forneceu IP)
- Ou configure redirecionamento para `www`

**Para subdomínio (`www.avalieganhe.app`):**
- Use registro **CNAME** apontando para `v90jqdh1.up.railway.app`

**Para email:**
- Use registro **TXT** com valores SPF/DKIM

---

**Pronto!** 🎉 Agora você sabe como configurar DNS! Se tiver dúvidas, me pergunte!

