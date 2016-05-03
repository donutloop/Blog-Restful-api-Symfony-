#!/bin/bash
sudo chmod 777 -R var/logs var/cache var/sessions
php bin/console cache:clear --env="dev"
php bin/console cache:clear --env="prod"
php bin/console cache:clear --env="test"
sudo chmod 777 -R var/logs var/cache var/sessions
