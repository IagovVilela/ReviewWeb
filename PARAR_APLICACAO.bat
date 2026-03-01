@echo off
chcp 65001 >nul 2>&1
setlocal enabledelayedexpansion

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║   ENCERRANDO APLICAÇÃO - Reviews Platform             ║
echo ╚════════════════════════════════════════════════════════╝
echo.

echo [1/5] Parando processos na porta 8000 (Laravel)...
set FOUND=0
for /f "tokens=5" %%a in ('netstat -ano 2^>nul ^| findstr :8000 ^| findstr LISTENING') do (
    set FOUND=1
    echo    Encontrado processo: %%a
    taskkill /F /PID %%a >nul 2>&1
    if errorlevel 1 (
        echo    ⚠️  Nao foi possivel parar o processo %%a
    ) else (
        echo    ✅ Processo %%a encerrado
    )
)
if !FOUND!==0 (
    echo    ℹ️  Nenhum processo encontrado na porta 8000
)
echo.

echo [2/5] Parando processos na porta 4040 (Ngrok)...
set FOUND=0
for /f "tokens=5" %%a in ('netstat -ano 2^>nul ^| findstr :4040 ^| findstr LISTENING') do (
    set FOUND=1
    echo    Encontrado processo: %%a
    taskkill /F /PID %%a >nul 2>&1
    if errorlevel 1 (
        echo    ⚠️  Nao foi possivel parar o processo %%a
    ) else (
        echo    ✅ Processo %%a encerrado
    )
)
if !FOUND!==0 (
    echo    ℹ️  Nenhum processo encontrado na porta 4040
)
echo.

echo [3/5] Parando processos ngrok.exe...
taskkill /F /IM ngrok.exe >nul 2>&1
if errorlevel 1 (
    echo    ℹ️  Nenhum processo ngrok encontrado
) else (
    echo    ✅ Processos ngrok encerrados
)
echo.

echo [4/5] Parando processos php.exe (servidor Laravel)...
taskkill /F /IM php.exe >nul 2>&1
if errorlevel 1 (
    echo    ℹ️  Nenhum processo PHP encontrado
) else (
    echo    ✅ Processos PHP encerrados
)
echo.

echo [5/5] Verificando se tudo parou...
timeout /t 2 /nobreak >nul

netstat -ano 2>nul | findstr ":8000 :4040" >nul 2>&1
if errorlevel 1 (
    echo    ✅ Nenhum processo encontrado nas portas 8000 e 4040
    set ALL_STOPPED=1
) else (
    echo    ⚠️  Ainda existem processos rodando
    echo    Verifique manualmente: netstat -ano ^| findstr ":8000 :4040"
    set ALL_STOPPED=0
)
echo.

echo ╔════════════════════════════════════════════════════════╗
if !ALL_STOPPED!==1 (
    echo ║   ✅ APLICAÇÃO ENCERRADA COM SEGURANÇA!                ║
) else (
    echo ║   ⚠️  ALGUNS PROCESSOS AINDA ESTÃO RODANDO            ║
)
echo ╚════════════════════════════════════════════════════════╝
echo.
echo Agora voce pode desligar o PC com seguranca.
echo.
echo DICAS:
echo    - O MySQL pode continuar rodando (nao ha problema)
echo    - Para para-lo, use o Laragon (Menu ^> MySQL ^> Stop)
echo    - Se quiser fazer backup antes, execute: .\scripts\backup_database.bat
echo.
pause





