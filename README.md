# Test yii2 API project

## Развертывание проекта

1. Склонировать проект: git clone https://github.com/ilya-yar/hospital-api.git
2. Перейти в директорию проекта: cd hospital-api
3. Перейти в папку приложения: cd app.
4. Установить нужные пакеты: composer install
5. Задать права на директорию runtime: sudo chmod 777 app/runtime/
6. Задать права на директорию c ассетами: sudo chmod 777 -R app/web/assets/
7. Запустить оккружение: docker-compose -f ../docker/docker-compose.yml up -d
8. Развернуть дамп БД: cat db.sql | docker exec -i docker_db_1 /usr/bin/mysql -u admin --password=admin test_db

## Работа с API:

Запрос к списку пациентов чере CURL:
curl http://localhost/patients-api -G --data 'name=test&polyclinic_id=2&birthday=1996-01-01' --header 'Authorization: Bearer YpBGl_EWteH_sAUPHVWTpbgdJDE7mrXy'

Запрос на создание нового пациента через CURL:
curl -X POST -d 'name=test & birthday=1996-01-01 & polyclinic_id=2 & phone=89109698891' http://localhost/patients-api/create  --header 'Authorization: Bearer YpBGl_EWteH_sAUPHVWTpbgdJDE7mrXy'

Ссылка на коллекцию Postman:
https://elements.getpostman.com/redirect?entityId=16121644-da9c9ba0-d5ef-4b6a-9f84-1ccbed1eee01&entityType=collection
