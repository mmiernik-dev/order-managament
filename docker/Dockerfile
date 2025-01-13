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

#Copy env parameters
COPY .env.dev /var/www/html/.env
# Copy aplication code
COPY . .

# Composer dependency instalation
RUN composer install --prefer-dist --optimize-autoloader

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]
