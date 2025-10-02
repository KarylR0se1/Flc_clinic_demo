# Use official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions needed for Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring bcmath tokenizer xml curl \
    && apt-get clean

# Enable Apache rewrite for Laravel
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear caches & generate app key
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan key:generate

# Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations after setting up DB
RUN php artisan migrate --force

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
