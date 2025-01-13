FROM php:8.1-fpm

FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
git \
curl \
zip \
unzip \
libicu-dev \
libpq-dev \
libonig-dev \
libxml2-dev \
libzip-dev \
libcurl4-openssl-dev \
&& docker-php-ext-install intl opcache pdo pdo_mysql pdo_pgsql zip

RUN pecl install redis \
    && docker-php-ext-enable redis

# Composer instalation
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --no-scripts --optimize-autoloader

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]




