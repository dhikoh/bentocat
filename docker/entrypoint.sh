#!/bin/sh
set -e

# Create storage symlink if it doesn't exist
php artisan storage:link --force

# Start supervisord to launch Nginx and PHP-FPM
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
