@echo off
chcp 65001 >nul 2>&1
title Ngrok - ReviewWeb

echo.
echo ============================================================
echo    NGROK - Tunnel para testar o sistema
echo ============================================================
echo.
echo Conectando a porta 8000 (Laravel)...
echo.
echo IMPORTANTE: A aplicacao deve estar rodando em http://localhost:8000
echo            (Laragon ou "php artisan serve")
echo.
echo Apos iniciar:
echo   - URL publica: copie em https://dashboard.ngrok.com ou na tela do ngrok
echo   - Painel local: http://127.0.0.1:4040
echo   - Use a URL publica no Stripe Webhook e para testar de outros dispositivos
echo.
echo ============================================================
echo.

where ngrok >nul 2>&1
if errorlevel 1 (
    echo [ERRO] ngrok nao encontrado no PATH!
    echo.
    echo Instale o ngrok:
    echo   1. Baixe em https://ngrok.com/download
    echo   2. Extraia ngrok.exe e coloque numa pasta no PATH
    echo      ou execute este .bat na mesma pasta do ngrok.exe
    echo.
    pause
    exit /b 1
)

ngrok http 8000

echo.
echo Ngrok encerrado.
pause
