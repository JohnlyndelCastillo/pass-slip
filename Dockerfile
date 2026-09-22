# Use official PHP + Apache image as base
FROM php:8.2-apache

# Install mysqli and PDO MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable mod_rewrite so docker/apache-hardening.conf can route requests
# through index.php (needed for the clean-URL front controller)
RUN a2enmod rewrite

# Copy project files into the container (optional if mounting volume)
# COPY . /var/www/html

# Set working directory (optional)
WORKDIR /var/www/html