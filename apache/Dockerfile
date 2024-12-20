FROM php:8.2-apache 

RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql

# Enable mod_rewrite : module pour utiliset htaccess
RUN a2enmod rewrite

# Copy custom Apache configuration 
COPY default.conf /etc/apache2/sites-available/000-default.conf

# Set permissions and restart configuration 
RUN chmod -R 755 /var/www/html