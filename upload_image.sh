#!/bin/bash

# Stop execution if a step fails
set -e

IMAGE_NAME=git.fe.up.pt:5050/lbaw/lbaw2324/lbaw23144 # Replace with your group's image name

# Ensure that dependencies are available
composer install
php artisan config:clear
php artisan clear-compiled
php artisan optimize

# docker buildx build --push --platform linux/amd64 -t $IMAGE_NAME .
docker build -t $IMAGE_NAME .
docker push $IMAGE_NAME

#docker run -it -p 8000:80 --name=lbaw23144 -e DB_DATABASE="lbaw23144" -e DB_SCHEMA="lbaw23144" -e DB_USERNAME="lbaw23144" -e DB_PASSWORD="iWCUeimk" git.fe.up.pt:5050/lbaw/lbaw2324/lbaw23144 # Replace with your group's image name
