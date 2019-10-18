FROM composer:1

RUN mkdir /workdir
RUN mkdir /workdir/src
COPY next.php /workdir
COPY src/ /workdir/src
COPY ./composer.json /workdir
COPY ./composer.lock /workdir
WORKDIR /workdir

RUN composer install --ansi --no-progress --no-interaction --prefer-dist --no-dev

ENTRYPOINT ["php", "/workdir/next.php"]
