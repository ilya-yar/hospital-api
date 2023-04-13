# Test yii2 API project

## Развертывание проекта

1. Склонировать проект: git clone https://github.com/ilya-yar/hospital-api.git
2. Перейти в директорию проекта: cd hospital-api
3. Перейти в папку приложения: cd app.
4. Установить нужные пакеты: composer install
5. Задать права на директорию runtime: sudo chmod 777 app/runtime/
6. Задать права на директорию c ассетами: sudo chmod 777 -R web/assets/
7. Запустить оккружение: docker-compose -f ../docker/docker-compose.yml up -d
8. Развернуть дамп БД: cat db.sql | docker exec -i docker_db_1 /usr/bin/mysql -u admin --password=admin test_db

Развернуть дамп БД: cat db.sql | docker exec -i docker_db_1 /usr/bin/mysql -u admin --password=admin test_db