#!/bin/bash

# Run migrations safely (no fresh, no drop)
php artisan migrate --force

# Start Nginx
service nginx start

# Start PHP-FPM (keeps container alive)
php-fpm
