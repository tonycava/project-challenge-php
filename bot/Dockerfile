FROM composer

WORKDIR /app

COPY composer.json .

RUN composer install

COPY . .

CMD ["composer", "run-script", "dev"]