@echo off
setlocal
title Cadastro Academico - Servidor Local

set "PROJECT_DIR=%~dp0"
set "PHP_EXE=C:\xampp\php\php.exe"
set "MONGODB_EXTENSION=%PROJECT_DIR%.runtime\php_mongodb.dll"

if not exist "%PHP_EXE%" (
  echo PHP do XAMPP nao foi encontrado em C:\xampp\php.
  echo Instale ou ajuste o caminho no arquivo INICIAR-SISTEMA.bat.
  pause
  exit /b 1
)

if not exist "%MONGODB_EXTENSION%" (
  echo A extensao MongoDB nao foi encontrada na pasta .runtime.
  pause
  exit /b 1
)

if not exist "%PROJECT_DIR%vendor\autoload.php" (
  echo As dependencias nao foram encontradas. Execute composer install nesta pasta.
  pause
  exit /b 1
)

if not exist "%PROJECT_DIR%.env" (
  echo O arquivo .env nao foi encontrado. Copie .env.example para .env e configure o Atlas.
  pause
  exit /b 1
)

if not exist "%PROJECT_DIR%.sessions" mkdir "%PROJECT_DIR%.sessions"

start "Servidor Cadastro Academico" "%PHP_EXE%" -d "extension=%MONGODB_EXTENSION%" -d extension=zip -d "session.save_path=%PROJECT_DIR%.sessions" -S 127.0.0.1:8000 -t "%PROJECT_DIR%"
timeout /t 2 /nobreak >nul
start "" http://127.0.0.1:8000
exit

