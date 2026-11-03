FROM php:8.2-apache

# Activar mod_rewrite de Apache
RUN a2enmod rewrite

# Permitir que .htaccess reescriba URLs
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copiar todo el proyecto a la carpeta del servidor
COPY . /var/www/html/

EXPOSE 80
