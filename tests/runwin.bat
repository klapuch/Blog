@echo off
php .\..\www\index.php orm:generate-proxies && .\..\vendor\bin\tester -j 8 -o console -s -c .\php.ini -p php .\