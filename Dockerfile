# ================ BUILDER ================
FROM php:8.3-cli AS builder

# Install system packages + Node.js 20
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP extensions (including gd for PhpSpreadsheet)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip bcmath gd opcache

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 1. Copy only composer files first (caching)
COPY composer.json composer.lock ./

# 2. Install dependencies WITHOUT running scripts yet (artisan not present)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

# 3. Now copy the entire project (artisan is here!)
COPY . .

# 4. Generate autoloader + run post-install scripts (now artisan exists)
RUN composer dump-autoload --optimize \
    && composer run-script post-autoload-dump \
    && composer run-script post-root-package-install \
    && composer run-script post-create-project-cmd

# 5. Build frontend assets
RUN npm ci --legacy-peer-deps && npm run build && rm -rf node_modules

# ================ FINAL IMAGE ================
FROM php:8.3-apache

# Runtime dependencies only
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Apache rewrite
RUN a2enmod rewrite

# Copy built application
COPY --from=builder /app /var/www/html

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Laravel public folder as document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Railway uses $PORT
EXPOSE 8080
ENV PORT=8080

CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && \
    apache2-foreground