@echo off
chcp 65001 >nul 2>&1
setlocal enabledelayedexpansion

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║   VERIFICAÇÃO DE STATUS DO SISTEMA                     ║
echo ║   Reviews Platform                                     ║
echo ╚════════════════════════════════════════════════════════╝
echo.

cd /d "%~dp0reviews-platform"

set ALL_OK=1

echo [1/6] Verificando MySQL...
netstat -ano | findstr :3306 >nul 2>&1
if errorlevel 1 (
    echo    ❌ MySQL NAO esta rodando!
    echo    Solucao: Inicie o MySQL no Laragon (Menu ^> MySQL ^> Start)
    set ALL_OK=0
) else (
    echo    ✅ MySQL esta rodando na porta 3306
)
echo.

echo [2/6] Verificando arquivo .env...
if exist ".env" (
    echo    ✅ Arquivo .env existe
) else (
    echo    ❌ Arquivo .env NAO existe!
    echo    💡 Solucao: Copie .env.example para .env
    set ALL_OK=0
)
echo.

echo [3/6] Verificando conexao com banco...
if exist "test_mysql_connection.php" (
    php test_mysql_connection.php >nul 2>&1
    if errorlevel 1 (
        echo    ❌ Falha na conexao com o banco!
        echo    💡 Execute: php test_mysql_connection.php para mais detalhes
        set ALL_OK=0
    ) else (
        echo    ✅ Conexao com banco OK
    )
) else (
    echo    ⚠️  Script de teste nao encontrado
)
echo.

echo [4/6] Verificando dependencias...
if exist "vendor\autoload.php" (
    echo    ✅ Dependencias instaladas
) else (
    echo    ❌ Dependencias NAO instaladas!
    echo    💡 Solucao: Execute: composer install
    set ALL_OK=0
)
echo.

echo [5/6] Verificando migracoes...
php artisan migrate:status 2>nul | findstr "Pending" >nul
if errorlevel 1 (
    echo    ✅ Nenhuma migracao pendente
) else (
    echo    ⚠️  Existem migracoes pendentes
    echo    💡 Execute: php artisan migrate
)
echo.

echo [6/6] Verificando storage...
if exist "public\storage" (
    echo    ✅ Link do storage existe
) else (
    echo    ⚠️  Link do storage nao existe
    echo    💡 Solucao: Execute: php artisan storage:link
)
echo.

echo ╔════════════════════════════════════════════════════════╗
if !ALL_OK!==1 (
    echo ║   ✅ SISTEMA PRONTO PARA INICIAR!                    ║
) else (
    echo ║   ⚠️  CORRIJA OS PROBLEMAS ACIMA ANTES DE CONTINUAR ║
)
echo ╚════════════════════════════════════════════════════════╝
echo.
pause





