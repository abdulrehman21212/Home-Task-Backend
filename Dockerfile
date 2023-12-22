# Use the official PHP image as the base image
FROM php:8.2.0-fpm

# Set the working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . .

# Install dependencies
RUN composer install

# Set up permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/storage/framework/sessions  /var/www/html/storage/framework/views

# Optionally, set the appropriate permissions explicitly
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views
RUN chown -R www-data:www-data storage/framework/views
RUN chmod -R 775 storage/framework/views
# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Set up Laravel application key
RUN php artisan key:generate



COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Make the entrypoint script executable
RUN chmod +x /usr/local/bin/entrypoint.sh

# Define the entrypoint script to be run when the container starts
ENTRYPOINT ["entrypoint.sh"]

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
