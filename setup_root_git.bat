@echo off
cd ..\..\..
if exist wp-content\themes\docy\db_dump.sql move wp-content\themes\docy\db_dump.sql knowlege.sql
if exist wp-content\themes\docy\.git rmdir /s /q wp-content\themes\docy\.git
git init
git remote add origin https://github.com/sarthak15092003/knowlege-base.git
git add .
git commit -m "Full site backup (Core + DB)"
git branch -M main
git push -u origin main
