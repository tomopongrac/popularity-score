FROM php:7.4.14-apache

COPY .docker/php/php.ini /usr/local/etc/php/
COPY . /var/www/html
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/composer-installer.sh /usr/local/bin/composer-installer
RUN chmod +x /usr/local/bin/composer-installer \
    && /usr/local/bin/composer-installer \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer --version
RUN docker-php-ext-install pdo_mysql \
	    && a2enmod rewrite negotiation