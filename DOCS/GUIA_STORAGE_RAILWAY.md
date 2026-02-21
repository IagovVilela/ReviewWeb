# Persistir imagens (logos) no Railway

## Problema

No Railway, o sistema de arquivos do container é **efêmero**: a cada redeploy a pasta `storage/` é recriada e os arquivos enviados (logos, imagens de fundo) são perdidos. Por isso as imagens deixam de carregar e aparece no console algo como:

`Erro ao carregar imagem: https://www.avalieganhe.app/storage/logos/logos_xxx.png`

## Solução recomendada: Volume no Railway

Use um **Volume** do Railway para que a pasta `storage` (ou só a parte pública) persista entre deploys.

### Passos

1. **Abra o projeto no Railway**  
   Acesse [railway.app](https://railway.app), entre no seu projeto e deixe a tela do projeto aberta (onde aparecem os serviços/cards).

2. **Abra a opção de criar Volume** (escolha uma das formas):
   - **Opção A:** Aperte **`Ctrl+K`** (Windows/Linux) ou **`Cmd+K`** (Mac) para abrir a **Command Palette**. Digite **"volume"** e escolha a ação de **criar volume** (ex.: "Create Volume" / "New Volume").
   - **Opção B:** Clique com o **botão direito** em uma área vazia do canvas do projeto (onde ficam os serviços). No menu que abrir, escolha a opção de **criar volume** (ex.: "Add Volume" / "New Volume").

3. **Vincule o volume ao serviço da aplicação**  
   Quando o Railway pedir, selecione o **serviço** onde roda a sua aplicação (ex.: o serviço do `reviews-platform` ou o nome que você deu ao app).

4. **Configure o Mount Path**  
   O volume precisa ser montado exatamente onde o Laravel grava os arquivos (pasta `storage`).
   - No Railway, o código costuma ficar em **`/app`** (Nixpacks).
   - Use um destes valores no campo **Mount Path**:
     - **`/app/storage`** (caminho absoluto, recomendado), ou  
     - **`storage`** (se o Railway aceitar caminho relativo ao projeto).
   - Salve/confirme a criação do volume.

   **Se não encontrar a opção de Volume:** confira se você está no projeto certo e no dashboard que mostra os serviços (não só variáveis). Em alguns planos a opção pode estar em **Settings** do serviço → **Volumes**. A documentação oficial: [docs.railway.app/deploy/volumes](https://docs.railway.app/deploy/volumes).

5. **Redeploy**
   - Faça um novo deploy. Na primeira vez com o volume, a pasta pode vir vazia.
   - Garanta que o deploy cria a estrutura e o link simbólico:
     - No **Dockerfile** ou no comando de **build**, já deve existir algo como `php artisan storage:link` (ou rode isso no **start**).
     - Se necessário, no comando de **start** (ou um script de deploy):  
       `mkdir -p storage/app/public/logos storage/app/public/backgrounds && php artisan storage:link`

6. **Verificação**
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

---

## Botão "Baixar QR Code"

O download do QR Code é gerado no servidor (não usa mais biblioteca no navegador). Se ao clicar aparecer erro ou redirecionamento com mensagem de pacote não instalado:

1. Na pasta do projeto Laravel (ex.: `reviews-platform`), execute:  
   **`composer require endroid/qr-code`**
2. Faça o commit e um novo deploy no Railway.
