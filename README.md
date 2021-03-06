# Online Shop

This is an online shop based on the [shop layout](https://github.com/kenkioko/shop-layout.git).
It is written in [php](https://www.php.net/) using the [laravel framework ](https://laravel.com)

## Dependencies
* php version 7.3
* composer version 1.9
* laravel version 6.0
* npm version 6.13
* node version 12.16.1
* mysql version 5.7

## Getting started

### Setup database
The dev database server used is [mysql version 5.7](https://www.mysql.com/).
Use env.example file as a start point.

### Install dependancies (Composer update and npm install)
Install the dependancies using `composer install` and `npm install` commands.

To create links for node modules in `/public/` folder use `npm run create-links`.
Also run `php artisan storage:link` to make a symbolic link to `/storage/app/public/` folder.

## Running (dev only)
Run the laravel web server using artisan `php artisan serve`. Follow the [Laravel](https://laravel.com/docs/6.x) Docs for more info.

Use npm run scripts to optimize code for production.
