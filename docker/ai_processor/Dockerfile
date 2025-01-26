FROM php:8.1-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y git curl libpng-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
