# Use official PHP FPM image
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

# Copy only composer files for caching
COPY composer.json composer.lock /app/

# Set temporary APP_KEY to allow artisan scripts to run
ENV APP_KEY=base64:TempKeyForBuildOnly1234567890abcd==

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --prefer-dist

# Copy the rest of the application
COPY . /app

# Install Node dependencies for Vite/Livewire
RUN npm ci --omit=dev

# Build frontend assets
RUN npm run build

# Expose port for Laravel
EXPOSE 8000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
