#!/bin/bash

php artisan octan:stop

git pull
sudo chown -R www-data:www-data /var/www/mapsis-srv.com

composer install --no-progress --no-interaction

php artisan clear-compiled
php artisan optimize:clear
php artisan route:clear
php artisan cache:clear

php artisan octane:start &

exit;