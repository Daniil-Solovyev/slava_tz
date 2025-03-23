Проект располагается вместе с конфигами для docker. Само laravel-приложение находится в директории src.

**ЗАПУСК**: <br>

В директории С ПРОЕКТОМ выполнить:
- docker-compose build
- docker-compose up -d
- docker exec -it slava_tz_php_container composer install

В директории SRC:
- копировать .env.example в .env <br>
- docker exec -it <container_name> php artisan migrate --seed
- создать БД testing.sqlite в директории database

**URLs**: <br>
http://localhost:8080/upload - загрузка файла <br>
http://localhost:8080/rows - вывод сгруппированых данных <br>
http://localhost:8080/get-progress?key=progress_key... - ссылка на счетчик в redis. Генерится при загрузке файла


**BASIC AUTH**: <br>
test@example.com <br>
password

result.txt находится по адресу logs/result.txt

тесты - php artisan test
