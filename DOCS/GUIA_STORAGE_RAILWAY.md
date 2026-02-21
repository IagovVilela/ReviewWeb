# Persistir imagens (logos) no Railway

## Problema

No Railway, o sistema de arquivos do container é **efêmero**: a cada redeploy a pasta `storage/` é recriada e os arquivos enviados (logos, imagens de fundo) são perdidos. Por isso as imagens deixam de carregar e aparece no console algo como:

`Erro ao carregar imagem: https://www.avalieganhe.app/storage/logos/logos_xxx.png`

## Solução recomendada: Volume no Railway

Use um **Volume** do Railway para que a pasta `storage` (ou só a parte pública) persista entre deploys.

### Passos

1. **No dashboard do Railway**, abra o seu projeto e o serviço da aplicação (ex.: `reviews-platform`).

2. **Adicione um Volume**
   - Aba **Variables** ou **Settings** do serviço.
   - Procure por **Volumes** (ou **Persistent Storage**).
   - Clique em **Add Volume** (ou **New Volume**).

3. **Configure o mount path**
   - O volume precisa ser montado no mesmo caminho onde a aplicação grava os arquivos.
   - No Laravel, isso é `storage` dentro da raiz do projeto (ex.: `/app/storage` se a raiz no container for `/app`).
   - **Mount Path** sugerido: `storage`
     - Se o Railway pedir caminho absoluto, use ex.: `/app/storage` (confirme no seu Dockerfile ou build qual é a raiz; muitas vezes é `/app`).

4. **Redeploy**
   - Faça um novo deploy. Na primeira vez com o volume, a pasta pode vir vazia.
   - Garanta que o deploy cria a estrutura e o link simbólico:
     - No **Dockerfile** ou no comando de **build**, já deve existir algo como `php artisan storage:link` (ou rode isso no **start**).
     - Se necessário, no comando de **start** (ou um script de deploy):  
       `mkdir -p storage/app/public/logos storage/app/public/backgrounds && php artisan storage:link`

5. **Verificação**
   - Após o deploy, envie um novo logo por uma empresa e faça um redeploy.
   - A imagem deve continuar acessível em `https://seu-dominio/storage/logos/...`.

### Observações

- **Primeiro deploy com volume:** o diretório montado pode começar vazio. O `mkdir -p` acima garante que `logos` e `backgrounds` existam.
- **Já existem arquivos no container antes do volume:** no primeiro attach do volume, o conteúdo atual de `storage` pode ser “trocado” pelo do volume (vazio). Ou seja, o que já estava lá se perde uma vez; daí em diante tudo que for salvo ficará no volume.
- Se o Railway mostrar o caminho absoluto do projeto no container (ex. em logs ou documentação), use esse valor como base do mount path (ex. `/app/storage`).

## Alternativa: S3 (ou compatível)

Para não depender do disco do container, dá para usar **Amazon S3** (ou um compatível, ex. DigitalOcean Spaces, MinIO):

1. Crie um bucket e obtenha as chaves (Access Key, Secret, região, nome do bucket).
2. No Railway, defina as variáveis (ex.: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`, e se precisar `AWS_URL`).
3. No projeto, o disco `s3` já está em `config/filesystems.php`. Para passar a usar S3 para logos e imagens de fundo seria necessário:
   - Configurar um disco (ex. `public` ou `media`) que use S3 em produção, e
   - Trocar no código as chamadas de `Storage::disk('public')` para esse disco quando for logo/background.

Isso exige alteração de código e testes; a opção mais rápida costuma ser o **Volume** acima.

## Resumo

| Causa do erro | O que fazer |
|---------------|-------------|
| Redeploy apaga `storage/` | Usar **Volume** do Railway montado em `storage` (ou `/app/storage`) |
| CDN ou cache antigo | Verificar se a URL da imagem está correta e se o link `public/storage` → `storage/app/public` existe após o deploy |

Depois de configurar o volume e garantir `storage:link` e pastas `logos`/`backgrounds`, as imagens devem persistir entre redeploys.
