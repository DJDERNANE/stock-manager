FROM php:8.2-cli

WORKDIR /var/www

# Copy application
COPY . .

# Install Composer and dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]