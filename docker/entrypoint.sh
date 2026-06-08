#!/bin/sh
set -e

# Create storage symlink if it doesn't exist
php artisan storage:link --force

# Ensure persistent directories exist inside storage
mkdir -p /var/www/html/storage/app/public/uploads/products
chown -R www-data:www-data /var/www/html/storage/app/public/uploads

# Create symlink public/uploads -> storage/app/public/uploads if it doesn't exist
if [ ! -L /var/www/html/public/uploads ]; then
    rm -rf /var/www/html/public/uploads
    ln -s /var/www/html/storage/app/public/uploads /var/www/html/public/uploads
fi
chown -h www-data:www-data /var/www/html/public/uploads

# Run database migrations
php artisan migrate --force

# Start supervisord to launch Nginx and PHP-FPM
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
