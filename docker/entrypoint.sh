#!/bin/sh
set -e

# Create storage symlink if it doesn't exist
php artisan storage:link --force

# Ensure uploads folder has correct ownership at runtime (in case of docker volumes)
mkdir -p /var/www/html/public/uploads
chown -R www-data:www-data /var/www/html/public/uploads

# Start supervisord to launch Nginx and PHP-FPM
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
