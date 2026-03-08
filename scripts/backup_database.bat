@echo off
chcp 65001 >nul 2>&1
setlocal enabledelayedexpansion

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║   BACKUP DO BANCO DE DADOS - Reviews Platform         ║
echo ╚════════════════════════════════════════════════════════╝
echo.

cd /d "%~dp0reviews-platform"

REM Verificar se MySQL está rodando
netstat -ano | findstr :3306 >nul 2>&1
if errorlevel 1 (
    echo [ERRO] MySQL nao esta rodando!
    echo Por favor, inicie o MySQL no Laragon (Menu ^> MySQL ^> Start) primeiro.
    echo.
    pause
    exit /b 1
)

REM Criar pasta de backups
if not exist "backups" mkdir backups

REM Gerar nome do arquivo com data/hora
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set datetime=%%I
set datestamp=!datetime:~0,8!
set timestamp=!datetime:~8,6!
set filename=backup_!datestamp!_!timestamp!.sql

echo [1/3] Fazendo backup do banco de dados...
echo Arquivo: backups\!filename!
echo.

REM Ler configurações do .env
set DB_NAME=reviews_platform
set DB_USER=root
set DB_PASS=

if exist ".env" (
    for /f "tokens=1,2 delims==" %%a in ('.env') do (
        if "%%a"=="DB_DATABASE" set DB_NAME=%%b
        if "%%a"=="DB_USERNAME" set DB_USER=%%b
        if "%%a"=="DB_PASSWORD" set DB_PASS=%%b
    )
)

REM Fazer backup
if "!DB_PASS!"=="" (
    mysqldump -u !DB_USER! !DB_NAME! > "backups\!filename!"
) else (
    mysqldump -u !DB_USER! -p!DB_PASS! !DB_NAME! > "backups\!filename!"
)

if errorlevel 1 (
    echo [ERRO] Falha ao fazer backup!
    echo Verifique se o MySQL esta rodando e se as credenciais estao corretas.
    echo.
    pause
    exit /b 1
)

echo [OK] Backup criado com sucesso!
echo.

REM Verificar tamanho do arquivo
for %%A in ("backups\!filename!") do set size=%%~zA
set /a sizeKB=!size!/1024

echo [2/3] Informacoes do backup:
echo   Arquivo: backups\!filename!
echo   Tamanho: !sizeKB! KB
echo.

REM Manter apenas os últimos 10 backups
echo [3/3] Limpando backups antigos (mantendo os ultimos 10)...
for /f "skip=10 delims=" %%F in ('dir /b /o-d backups\backup_*.sql 2^>nul') do del "backups\%%F"
echo [OK] Limpeza concluida!
echo.

echo ╔════════════════════════════════════════════════════════╗
echo ║   ✅ BACKUP CONCLUIDO COM SUCESSO!                    ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo O backup foi salvo em: backups\!filename!
echo.
pause





