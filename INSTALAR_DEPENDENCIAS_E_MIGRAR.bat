@echo off
title ReviewWEB - Instalar dependencias e migrar banco
chcp 65001 >nul 2>&1
setlocal enabledelayedexpansion

echo.
echo ========================================
echo   Instalar dependencias + Migrar banco
echo ========================================
echo.

REM --- Usar PHP do Laragon quando existir (mesma logica do INICIAR_APLICACAO.bat) ---
set "LARAGON_PHP="
if defined LARAGON_ROOT (
    for /d %%d in ("%LARAGON_ROOT%\bin\php\php-*") do (
        set "LARAGON_PHP=%%d"
        goto :laragon_path_done
    )
)
if not defined LARAGON_PHP if exist "C:\laragon\bin\php\" (
    for /d %%d in ("C:\laragon\bin\php\php-*") do (
        set "LARAGON_PHP=%%d"
        goto :laragon_path_done
    )
)
if not defined LARAGON_PHP if exist "D:\laragon\bin\php\" (
    for /d %%d in ("D:\laragon\bin\php\php-*") do (
        set "LARAGON_PHP=%%d"
        goto :laragon_path_done
    )
)
if not defined LARAGON_PHP if exist "C:\laragon\bin\php\php-8.2.0" set "LARAGON_PHP=C:\laragon\bin\php\php-8.2.0"
if not defined LARAGON_PHP if exist "C:\laragon\bin\php\php-8.1.0" set "LARAGON_PHP=C:\laragon\bin\php\php-8.1.0"
if not defined LARAGON_PHP if exist "C:\laragon\bin\php\php-8.3.0" set "LARAGON_PHP=C:\laragon\bin\php\php-8.3.0"
if not defined LARAGON_PHP if exist "C:\laragon\bin\php\php-8.4.0" set "LARAGON_PHP=C:\laragon\bin\php\php-8.4.0"
:laragon_path_done
if defined LARAGON_PHP (
    set "PATH=!LARAGON_PHP!;%PATH%"
    if defined LARAGON_ROOT set "PATH=%LARAGON_ROOT%\bin;%PATH%"
    echo [INFO] PHP do Laragon: !LARAGON_PHP!
) else (
    echo [INFO] Usando PHP do PATH do sistema.
)
echo.

php -v >nul 2>&1
if errorlevel 1 (
    echo [ERRO] PHP nao encontrado. Abra o Laragon e clique em "Start All" ou adicione PHP ao PATH.
    pause
    exit /b 1
)

cd /d "%~dp0reviews-platform"
if errorlevel 1 (
    echo [ERRO] Pasta reviews-platform nao encontrada.
    pause
    exit /b 1
)
echo [INFO] Diretorio: %cd%
echo.

echo [1/2] composer update (atualiza lock file e instala dependencias)...
echo.
call composer update --no-interaction
if errorlevel 1 (
    echo [ERRO] composer update falhou.
    pause
    exit /b 1
)
echo.
echo [2/2] php artisan migrate...
echo.
call php artisan migrate --force
if errorlevel 1 (
    echo [ERRO] migrate falhou.
    pause
    exit /b 1
)
echo.
echo ========================================
echo   Concluido: dependencias e banco OK.
echo ========================================
echo.
pause
