FROM composer:latest

WORKDIR /var/www/laravel-project

ENTRYPOINT ["composer", "--ignore-platform-reqs"]
