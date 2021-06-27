FROM composer:2.0.9 AS composer
FROM spiralscout/roadrunner:1.9.0 AS roadrunner

FROM php:8.0.2-alpine
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=roadrunner /usr/bin/rr /usr/bin/rr

RUN set -xe \
    && apk add --no-cache build-base \
                        autoconf \
                        libc-dev \
                        pcre-dev \
                        pkgconf \
                        libaio \
                        libnsl \
                        cmake \
                        make \
                        file \
                        re2c \
                        g++ \
                        gcc \
                        curl \
                        openrc \
                        zlib \
                        icu \
                        gettext \
                        freetds \
                        unixodbc \
                        libc6-compat \
                        gcompat \
                        busybox-initscripts \
    && apk add --no-cache --virtual .build-deps gettext-dev \
                                                icu-dev \
                                                zlib-dev \
                                                libintl \
                                                unixodbc-dev \
                                                freetds-dev \
                                                freetype-dev \
                                                libjpeg-turbo-dev \
                                                libpng-dev \
    && apk add --no-cache --repository http://dl-3.alpinelinux.org/alpine/edge/community gnu-libiconv \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-configure intl --enable-intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        sockets \
        gettext \
        opcache \
        bcmath \
        pcntl \
        intl \
    && apk del .build-deps \
    && rm -rf /app /home/user ${COMPOSER_HOME} /var/cache/apk/* \
    && mkdir /app /home/user ${COMPOSER_HOME} \
    && ln -s /usr/bin/composer /usr/bin/c

COPY /docker/app-entrypoint.sh /app/docker/app-entrypoint.sh

WORKDIR /
COPY ./composer.* /app/
WORKDIR /app
RUN set -xe \
    && composer install --no-interaction --no-ansi --no-suggest --prefer-dist  --no-autoloader --no-scripts --ignore-platform-reqs \
    && composer install --no-dev --no-interaction --no-ansi --no-suggest --prefer-dist  --no-autoloader --no-scripts --ignore-platform-reqs \
    && chmod -R 777 /home/user ${COMPOSER_HOME}

WORKDIR /
COPY . /app/
WORKDIR /app

RUN set -xe \
    && chmod +x /app/docker/app-entrypoint.sh \
    && composer --version \
    && php -v \
    && php -m \
    && rr -h \
    && composer dump
EXPOSE 8080
ENTRYPOINT ["/app/docker/app-entrypoint.sh"]
CMD ["/usr/bin/rr", "serve", "-c", "/app/.rr.yml"]
