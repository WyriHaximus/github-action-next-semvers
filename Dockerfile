FROM composer:1

RUN mkdir /workdir
COPY ./next.php /workdir
COPY ./composer.json /workdir
COPY ./composer.lock /workdir
WORKDIR /workdir

RUN composer install --ansi --no-progress --no-interaction --prefer-dist

ENTRYPOINT ["php", "/workdir/next.php"]
