# Imagens no Cloudinary (persistência após redeploy)

Quando as variáveis do Cloudinary estão configuradas, **logos** e **imagens de fundo** das empresas são enviadas para a nuvem. Assim as imagens **não somem** após um redeploy no Railway.

## Variáveis no `.env` (e no Railway)

Adicione no seu `.env` local e nas **Variables** do serviço no Railway:

```env
CLOUDINARY_CLOUD_NAME=seu_cloud_name
CLOUDINARY_API_KEY=sua_api_key
CLOUDINARY_API_SECRET=sua_api_secret
```

Opcional (pasta no Cloudinary onde as imagens ficam):

```env
CLOUDINARY_FOLDER=avalieganhe
```

## Onde obter os valores

1. Acesse [Cloudinary Console](https://cloudinary.com/console).
2. No **Dashboard** você vê:
   - **Cloud name**
   - **API Key**
   - **API Secret** (clique em "Reveal" para copiar)

## Comportamento

- **Com Cloudinary configurado:** uploads de logo e imagem de fundo vão para o Cloudinary; o banco guarda a URL (ex.: `https://res.cloudinary.com/...`). Essas URLs continuam válidas após redeploy.
- **Sem Cloudinary:** continua usando o disco local (`storage/app/public`). No Railway, o conteúdo some a cada redeploy.

## Segurança

- **Nunca** commite o `.env` com as chaves.
- No Railway, use **Variables** do serviço para `CLOUDINARY_API_KEY` e `CLOUDINARY_API_SECRET`.
- Se a API Secret tiver sido exposta (ex.: em chat ou log), gere uma nova no Dashboard do Cloudinary.
