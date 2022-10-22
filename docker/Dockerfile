FROM php:8.1.5-fpm-alpine3.15

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]