# Use official PHP FPM image
FROM php:8.2-fpm

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libonig-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy only composer files first for caching
COPY composer.json composer.lock /app/

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --prefer-dist

# Copy the rest of the application
COPY . /app

# Install Node dependencies for Vite/Livewire
RUN npm ci --omit=dev

# Build frontend assets (Vite)
RUN npm run build

# Expose port (Railway handles routing automatically)
EXPOSE 8000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
