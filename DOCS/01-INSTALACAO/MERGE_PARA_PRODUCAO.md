# Merge da branch de implementação para a produção (Railway)

Este guia explica como **unir** a branch onde foi feita a implementação (ex.: **Stripe**) com a branch que está no ar no Railway (ex.: **PROD-IAGO(langingpage)**) de forma **segura**, sem perder código nem destruir branches.

---

## O que é o merge?

**Merge** = pegar o que foi feito em uma branch (ex.: Stripe) e **incorporar** em outra (ex.: PROD-IAGO).  
O Git **não apaga** nenhuma branch ao fazer merge. Ele só cria um **novo commit** na branch de destino que junta os dois históricos. As duas branches continuam existindo; você pode voltar a qualquer ponto no histórico.

- **Branch no ar (PROD):** continua sendo a “fonte da verdade” do que está em produção.
- **Branch Stripe:** continua existindo com todo o histórico; você pode seguir usando ou deletar depois, se quiser.

**Nada é “sobrescrito” de forma irreversível.** Se algo der errado, dá para voltar (revert ou reset), desde que você não faça **force push** na branch de produção nem **delete** a branch.

---

## O que acontece depois do merge?

1. A branch de **produção** (a que o Railway usa) passa a ter **todos os commits** que estavam na branch Stripe (loja, e-mail ao proprietário, Stripe, assinatura, etc.).
2. Se o Railway estiver configurado para fazer deploy **automático** quando essa branch for atualizada, ele vai **fazer um novo deploy** com o código novo.
3. As variáveis de ambiente que você já configurou no Railway **continuam valendo**; o merge não altera configuração do Railway, só o código.

---

## Riscos reais e como evitar

| Risco | Pode acontecer? | Como evitar |
|-------|------------------|-------------|
| **Perder a branch que está no ar** | Não, se você **não** fizer force push em PROD e **não** apagar a branch. | Não use `git push --force` na branch de produção. Não delete a branch PROD. |
| **Conflitos de merge** | Pode, se as **duas** branches alteraram o **mesmo trecho** do mesmo arquivo. | Normal. O Git avisa; você resolve arquivo por arquivo, testa e segue. |
| **Quebrar o site após o merge** | Só se o código novo tiver bug ou faltar variável de ambiente. | Testar em local ou em outro ambiente antes; depois do merge, conferir o deploy e a URL. |

**Garantia que posso dar:**  
Se você **não** fizer force push na branch de produção e **não** apagar essa branch, você **não** perde o que está no ar. O pior que pode acontecer é ter **conflitos** para resolver (o Git mostra quais arquivos) ou o deploy quebrar por bug/config, e isso se corrige com novo commit ou revert.

---

## Passo a passo seguro (merge Stripe → PROD)

Use a branch que o **Railway** está usando como “produção”. No seu repositório aparecem **PROD-IAGO(langingpage)** e **PROD-iago**; confira no Railway qual delas está configurada para deploy (ex.: `PROD-IAGO(langingpage)`).  
Abaixo, chamamos de **PROD** essa branch.

### 1. Garantir que está tudo commitado na branch Stripe

```bash
git checkout Stripe
git status
```

Se tiver arquivos não commitados, faça commit (ou stash):

```bash
git add .
git commit -m "Ajustes finais antes do merge para produção"
```

### 2. Fazer backup (tag) da branch de produção

Isso guarda o ponto exato em que a produção está hoje. Se precisar voltar, você sabe para onde.

```bash
git fetch origin
git checkout PROD-IAGO(langingpage)
git tag backup-prod-antes-merge-stripe-$(date +%Y%m%d)
git push origin backup-prod-antes-merge-stripe-$(date +%Y%m%d)
```

(No Windows, se `date` não funcionar, use um nome fixo, ex.: `backup-prod-antes-merge-stripe-20250302`.)

### 3. Atualizar a branch de produção e fazer o merge

```bash
git checkout PROD-IAGO(langingpage)
git pull origin PROD-IAGO(langingpage)
git merge Stripe -m "Merge branch Stripe: loja, Stripe, assinatura, e-mail proprietário"
```

- Se aparecer **“Already up to date”**: a PROD já tem tudo que está na Stripe; não precisa fazer mais nada no merge.
- Se aparecer **conflitos**: o Git lista os arquivos. Abra cada um, procure por `<<<<<<<`, `=======`, `>>>>>>>` e ajuste o código (deixe como deve ficar em produção). Depois:

  ```bash
  git add .
  git commit -m "Resolução de conflitos do merge Stripe -> PROD"
  ```

### 4. Enviar a branch de produção atualizada

```bash
git push origin PROD-IAGO(langingpage)
```

Se o Railway fizer deploy automático a partir dessa branch, o próximo deploy já será com o código da Stripe.

### 5. Conferir no Railway

- Ver se o deploy subiu sem erro.
- Abrir a URL do app e testar login, assinatura, loja, etc.
- Se algo quebrar: você pode reverter o merge (novo commit com `git revert`) ou voltar ao tag de backup e fazer force push **só em emergência** (e com cuidado).

---

## Resumo

- **Sim, é merge:** você une a branch **Stripe** na branch **PROD** (a que está no ar).
- **Depois do merge:** a PROD tem todo o código novo; o Railway faz novo deploy se estiver configurado para essa branch.
- **Branches não são “destruídas”** pelo merge; você não perde a branch no ar desde que não faça force push nem apague a branch.
- **Conflitos** podem existir se as duas branches mexeram no mesmo arquivo; o Git avisa e você resolve. Não é risco de “perder” a branch.
- **Segurança extra:** tag de backup na PROD antes do merge (passo 2).

Se quiser, na próxima vez que for fazer esse merge, podemos revisar juntos os arquivos em conflito (se surgir algum).
