FROM php:8-apache

# J'installe les extension nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# On active apache
RUN a2enmod rewrite

# On mets en place les permissions
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/

EXPOSE 80
