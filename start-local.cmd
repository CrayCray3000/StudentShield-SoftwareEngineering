@echo off
setlocal
cd /d "%~dp0web-app-ci4"
set "app.baseURL=http://127.0.0.1:8080/"
set "app.forceGlobalSecureRequests=false"
echo Student login: http://127.0.0.1:8080/login
echo Staff login:   http://127.0.0.1:8080/admin/login
echo Keep this window open. Press Ctrl+C to stop the local server.
php spark serve --host 127.0.0.1 --port 8080
endlocal
