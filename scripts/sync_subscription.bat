@echo off
chcp 65001 >nul 2>&1
setlocal

REM Usar PHP do Laragon quando existir
set "PHP_PATH="
if exist "C:\laragon\bin\php\php-8.4.0\php.exe" set "PHP_PATH=C:\laragon\bin\php\php-8.4.0"
if exist "C:\laragon\bin\php\php-8.3.0\php.exe" set "PHP_PATH=C:\laragon\bin\php\php-8.3.0"
if exist "C:\laragon\bin\php\php-8.2.0\php.exe" set "PHP_PATH=C:\laragon\bin\php\php-8.2.0"
if exist "C:\laragon\bin\php\php-8.1.0\php.exe" set "PHP_PATH=C:\laragon\bin\php\php-8.1.0"
if not defined PHP_PATH for /d %%d in ("C:\laragon\bin\php\php-*") do (set "PHP_PATH=%%d" & goto :done)
:done
if defined PHP_PATH set "PATH=%PHP_PATH%;%PATH%"

cd /d "%~dp0..\reviews-platform"
if not exist "artisan" (
    echo [ERRO] Pasta reviews-platform ou artisan nao encontrado.
    pause
    exit /b 1
)

set "EMAIL=teste@1.com"
if "%~1" neq "" set "EMAIL=%~1"

echo Sincronizando assinatura do usuario: %EMAIL%
echo.
php artisan billing:sync-subscription %EMAIL%
echo.
pause
