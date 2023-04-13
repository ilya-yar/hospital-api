# Test yii2 API project

## Развертывание проекта

Задать доступ папке с ассетами: cat db.sql | docker exec -i docker_db_1 /usr/bin/mysql -u admin --password=admin test_db

Развернуть дамп БД: cat db.sql | docker exec -i docker_db_1 /usr/bin/mysql -u admin --password=admin test_db