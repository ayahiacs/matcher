## About Matcher

A simple matcher task used to match property with search profile.

## Tech

This matcher is built using laravel 8, typically should run using php 8, or you may use docker to run it.

## Installation
### Using PHP/Composer:
```
composer install
cp .env.example .env                  
php artisan key:generate
```

### Using docker:
```
docker run --rm \
    -v "$(pwd)":/var/www/html \
    -w /var/www/html \
    laravelsail/php80-composer:latest \
    bash -c "composer install && cp .env.example .env && php artisan key:generate"
```

# Running

### Using PHP built in web server:
```
php artisan serve
```
```
php artisan test
```
### Using Docker
```
./vendor/bin/sail up
```
```
./vendor/bin/sail test
```
## Contact

[ayahiacs@gmail.com](mailto:ayahiacs@gmail.com).