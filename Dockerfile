FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL (pdo y pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite de Apache para el enrutamiento MVC
RUN a2enmod rewrite

# Copiar el código del proyecto al contenedor
COPY . /var/www/html/

# Configurar permisos para Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
