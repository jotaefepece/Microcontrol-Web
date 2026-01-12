FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Cambiar DocumentRoot de Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Copiar SOLO el backend
COPY solitario-con-backend/ranking-backend/ /var/www/html/

# Ajustar Apache al nuevo root
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
