FROM composer

WORKDIR /app

COPY .. .

RUN composer install

EXPOSE 8000

CMD ["composer", "run-script", "dev"]