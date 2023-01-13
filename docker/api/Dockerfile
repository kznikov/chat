FROM php:8.1.12-fpm-alpine3.16

RUN \
    set -ex && \
    curl -sfL https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
    chmod +x /usr/bin/composer && \
    composer self-update --clean-backups 2.4.4 && \
    apk update && \
    apk add --no-cache libstdc++ libpq && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev postgresql-dev openssl-dev pcre-dev pcre2-dev zlib-dev && \
    docker-php-ext-install pdo_mysql && \
    pecl channel-update pecl.php.net && \
    pecl install --configureoptions 'enable-redis-igbinary="no" enable-redis-lzf="no" enable-redis-zstd="no"' redis-5.3.7 && \
    docker-php-ext-enable redis && \
    apk add --no-cache supervisor nginx && \
    apk del .build-deps && \
    mkdir /var/www/api

ADD conf/ /

WORKDIR /var/www/api

CMD ["supervisord","-c","/etc/supervisor/supervisord.conf"]
