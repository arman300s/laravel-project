FROM composer:latest

WORKDIR /var/www/team-project

ENTRYPOINT ["composer", "--ignore-platform-reqs"]