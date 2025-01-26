# Use PHP 8.1 FPM
FROM php:8.1-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl nano procps \
    libpng-dev libonig-dev libxml2-dev libjpeg-dev libzip-dev \
    zip unzip mariadb-client supervisor cron \
    redis \
    && docker-php-ext-configure gd --enable-gd --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP Redis via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis

# XDebug (optional for development)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Create required directories for Supervisor
RUN mkdir -p /var/www/storage/logs /var/log/supervisor \
    && chmod -R 777 /var/www/storage /var/log/supervisor

# Copy Supervisor configuration
COPY docker/app/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Cron setup
COPY docker/app/laravel-cron /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron && crontab /etc/cron.d/laravel-cron

# Node.js installation (for frontend builds)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# PHP Config
COPY docker/app/php.ini /usr/local/etc/php/

# Expose PHP FPM port
EXPOSE 9000

COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
