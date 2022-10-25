FROM php:7.1-cli-alpine

COPY . .

COPY --from=composer:2.2  /usr/bin/composer /usr/bin/composer

RUN composer install

EXPOSE 8000

CMD ["composer", "run-script", "dev"]