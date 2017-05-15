@echo off
start cmd /k "cd/d D:\webroot\build_info\crawler\ && php crawler.php start"
::choice /t 5 /d y /n >nul