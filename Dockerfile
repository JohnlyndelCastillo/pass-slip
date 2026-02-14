# Use official PHP + Apache image as base
FROM php:8.2-apache

# Install mysqli and PDO MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files into the container (optional if mounting volume)
# COPY . /var/www/html

# Set working directory (optional)
WORKDIR /var/www/html
