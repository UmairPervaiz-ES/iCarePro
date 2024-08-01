#!/bin/bash

/etc/init.d/postgresql  start

php artisan migrate
php artisan db:seed
php artisan passport:install
php artisan l5-swagger:generate
php artisan storage:link

/etc/init.d/postgresql  stop
