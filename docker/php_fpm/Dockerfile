FROM php:8.3-fpm

ADD files/php.ini "$PHP_INI_DIR/php.ini"

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libgd-dev \
    libssh-dev \
    libicu-dev
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#Mine

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-external-gd
RUN docker-php-ext-install gd bcmath sockets
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', '/usr/bin/composer-setup.php');"
RUN php -r "if (hash_file('sha384', '/usr/bin/composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php /usr/bin/composer-setup.php --install-dir=/usr/bin
RUN php -r "unlink('/usr/bin/composer-setup.php');"
RUN mv /usr/bin/composer.phar /usr/bin/composer
RUN chmod +x /usr/bin/composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/task

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
