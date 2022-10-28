FROM composer

WORKDIR /app

COPY composer.json .

RUN composer install

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY . .

CMD ["composer", "run-script", "dev"]