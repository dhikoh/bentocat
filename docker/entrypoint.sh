#!/bin/sh
set -e

# Start supervisord to launch Nginx and PHP-FPM
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
