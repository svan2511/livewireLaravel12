# Use official PHP 8.2 FPM image
FROM php:8.2-fpm

# Set working directory
WORKDIR /app

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    zlib1g-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd bcmath mbstring xml opcache \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY . /app

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --prefer-dist

# Install Node dependencies and build assets
RUN npm ci --omit=dev
RUN npm run build

# Expose container port (Railway will map $PORT automatically)
EXPOSE 8000

# Start Laravel using Railway's dynamic port
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]