FROM php:7.4-cli-alpine

RUN apk update && apk add curl && rm -rf /var/cache/apk/*
RUN curl -LS https://github.com/sensiolabs-de/deptrac/releases/download/0.5.0/deptrac.phar -o deptrac.phar \
    && chmod +wx deptrac.phar \
    && mv deptrac.phar /usr/local/bin/deptrac

COPY php.ini ${PHP_INI_DIR}
