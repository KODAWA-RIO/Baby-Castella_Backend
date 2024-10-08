# Web base stage
FROM nginx:mainline AS web-base

# Copy to nginx configration file
COPY ./nginx/nginx.conf /etc/nginx/nginx.conf


# Web development Stage
FROM web-base AS web-dev

# Port Expose
EXPOSE 80

# Copy to configration directory
COPY ./nginx/conf-d/ /etc/nginx/conf/



# App-base Stage
FROM php:8.3.12-fpm AS app-base

# Port expose
EXPOSE 9000

# Install on depencity
RUN apt-get update
RUN apt-get install -y git libzip-dev zip

# Install to php extensions
RUN docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:lts /usr/bin/composer /usr/bin/composer

# Work
WORKDIR /var/app


# App-development Stage
FROM app-base AS app-dev

# Copy to development php.ini
COPY ./php/php_dev.ini  /usr/local/etc/php/php.ini

# Environments
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_PROCESS_TIMEOUT=3600

# WorkDir
WORKDIR /var/app

# Copy to composer.json and composer.lock
COPY ./php/composer/composer.json ./composer.json
COPY ./php/composer/composer.lock ./composer.lock

# copy to project files
COPY ./php/laravel/ ./

# Install packages
RUN composer install



# db base stage
FROM mysql:8.4.2 AS db-base

# Shell
SHELL [ "/bin/bash", "-c", "-l" ]

# Port Expose
EXPOSE 3306

# Copy to my.cnf files
COPY ./mysql/my.cnf /etc/my.cnf

# Common Environment
ENV MYSQL_DATABASE=Laravel
ENV TZ=Asia/Tokyo


# db development stage
FROM db-base AS db-dev

# Development Environments
ENV MYSQL_ROOT_PASSWORD=Laravel



FROM phpmyadmin:latest AS admin

# Port expose
EXPOSE 80

# Environments
ENV PMA_HOST=php-db
ENV PMA_USER=root
ENV PMA_PASSWORD=Laravel