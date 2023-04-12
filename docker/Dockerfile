FROM richarvey/nginx-php-fpm

ADD app /var/www/app

RUN rm -Rf /etc/nginx/sites-enabled/*

ADD docker/conf/nginx/site.conf /etc/nginx/sites-available/site.conf

# The /run/nginx directory does not exist on the latest alpine containers.
RUN mkdir -p /run/nginx

RUN ln -s /etc/nginx/sites-available/site.conf /etc/nginx/sites-enabled/site.conf
