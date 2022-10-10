FROM composer

WORKDIR /app

COPY .. .

RUN composer composer global require leafs/cli

RUN composer install

RUN composer update

CMD ["composer", "run-script", "dev"]