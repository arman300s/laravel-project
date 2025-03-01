FROM composer:latest

WORKDIR /var/www/library-app

ENTRYPOINT ["composer", "--ignore-platform-reqs"]
