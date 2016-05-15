#!/bin/bash
sudo chmod 777 -R var/logs var/cache var/sessions
php bin/console cache:clear --env=dev
php bin/console doctrine:database:drop --env=dev --force --if-exists
php bin/console doctrine:database:create --env=dev
php bin/console doctrine:schema:update --force --env=dev
php bin/console doctrine:fixtures:load  --env=dev  --fixtures=tests/AppBundle/DataFixtures/ORM
php bin/console cache:clear --env=dev
sudo chmod 777 -R var/logs var/cache var/sessions
