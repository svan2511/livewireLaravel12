#!/bin/bash

# Start Nginx (foreground mode)
nginx &

# Start PHP-FPM (foreground)
php-fpm -F
