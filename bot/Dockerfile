FROM composer

WORKDIR /app

COPY composer.json .

RUN composer install

RUN apt-get install php-mysql

COPY . .

CMD ["composer", "run-script", "dev"]