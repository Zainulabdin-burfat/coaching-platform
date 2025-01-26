#!/bin/sh
set -e

# Start PHP-FPM in the background
php-fpm &

# Start Supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
