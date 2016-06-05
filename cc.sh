#!/bin/bash

if [ ! -d "app" ]; then
   printf "Error: missing app directory\n"
   exit 1
fi
if [ ! -d "var/logs" ]; then
   printf "Error: missing var/logs directory\n"
   exit 2
fi
if [ ! -d "var/cache" ]; then
   printf "Error: missing var/cache directory\n"
   exit 3
fi
if [ ! -d "var/sessions" ]; then
   printf "Error: missing var/sessions directory\n"
   exit 4
fi

sudo chmod 777 -R var/logs var/cache var/sessions
php bin/console cache:clear --env="dev"
php bin/console cache:clear --env="prod"
php bin/console cache:clear --env="test"
sudo chmod 777 -R var/logs var/cache var/sessions
