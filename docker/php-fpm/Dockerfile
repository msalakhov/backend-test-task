FROM php:8.2-fpm

ADD conf.d /usr/local/etc/php/conf.d
RUN apt-get update && apt-get install -qqy wget libpq-dev && \
    pecl install xdebug && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo_pgsql pgsql opcache bcmath && \
    docker-php-ext-enable xdebug && \
    mkdir /home/www-data && \
    chmod 0777 /home/www-data && \
    usermod -u 1000 --home=/home/www-data www-data && \
    groupmod -g 1000 www-data

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    echo "date.timezone = \"Europe/Moscow\"" >> $PHP_INI_DIR/php.ini && \
    echo "memory_limit = 128M" >> $PHP_INI_DIR/conf.d/docker-php-memlimit.ini

WORKDIR /app
