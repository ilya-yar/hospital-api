# Базовый образ с nginx и php. TODO php и nginx лучше разнести в разные контейнеры
FROM richarvey/nginx-php-fpm

# Приложение
ADD app /var/www/app

# Удаляем конфиги сайтов которые есть в nginx
RUN rm -Rf /etc/nginx/sites-enabled/*

# Добавляем конфиг нашего приложения
ADD docker/conf/nginx/site.conf /etc/nginx/sites-available/site.conf

# The /run/nginx directory does not exist on the latest alpine containers.
RUN mkdir -p /run/nginx

# Logs directory
# RUN mkdir -p /var/www/app/runtime
# RUN chown -R www-data:www-data /var/www/app
# RUN chmod 755 /var/www/app/runtime
#RUN mkdir -p /var/www/app/runtime/cache
# Nginx logs
RUN mkdir /var/www/log
RUN touch /var/www/log/frontend-access.log
RUN touch /var/www/log/frontend-error.log

# Создаем символьную ссылку для включения конфига
RUN ln -s /etc/nginx/sites-available/site.conf /etc/nginx/sites-enabled/site.conf
