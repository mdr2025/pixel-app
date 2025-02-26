FROM php:8.2-fpm

RUN apt-get update -y && apt-get upgrade -y 

RUN apt-get install -y \
    apt-transport-https \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev && apt-get clean
    
RUN rm -rf /var/lib/apt/lists/*

RUN pecl install redis-5.3.7 \
	&& pecl install xdebug-3.2.1 \
	&& docker-php-ext-enable redis xdebug

RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd opcache zip \
    && docker-php-ext-enable mysqli pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd xdebug 

WORKDIR /usr/local/etc/php/conf.d/

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install exif

RUN apt-get update && apt-get install -y gnupg \
  && curl -sL https://deb.nodesource.com/setup_16.x | bash - \
  && apt-get install -y nodejs

#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug \

RUN apt-get install -y cron

ADD .crontab /etc/cron.d/cron
RUN chmod 0644 /etc/cron.d/cron
RUN touch /var/log/cron.log

WORKDIR /var/www/html/erp_system_backend



# Set up permissions
RUN chown -R www-data:www-data /var/www/html/erp_system_backend
RUN chmod -R g+w /var/www/html/erp_system_backend

# RUN chown -R www-data:www-data /var/www/html/erp_system_backend/storage
# RUN chmod -R 755 /var/www/html/erp_system_backend/storage

# Install project dependencies
# RUN composer install --no-interaction --optimize-autoloader

CMD cron && docker-php-entrypoint php-fpm