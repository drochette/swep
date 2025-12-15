FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www

# Get nodejs
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -

# Install dependencies
RUN apt update && apt install -y build-essential curl git locales unzip vim zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for symfony application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php server
EXPOSE 9000
CMD ["php-fpm"]
