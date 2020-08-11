FROM php:7.4.9-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libxext6 \
    libxrender1 \
    libfontconfig \
    xfonts-utils

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 qpcompany
RUN useradd -u 1000 -ms /bin/bash -g qpcompany qpcompany

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=qpcompany:qpcompany . /var/www

USER qpcompany

EXPOSE 9000

CMD ["php-fpm"]