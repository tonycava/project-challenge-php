FROM composer

WORKDIR /app

COPY . .

RUN composer install

RUN composer update

CMD ["composer", "run-script", "dev"]