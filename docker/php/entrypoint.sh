#!/bin/bash

mkdir -p /var/www/runtime/log
chown -R www-data:www-data /var/www/runtime
chmod -R 755 /var/www/runtime

exec "$@"
