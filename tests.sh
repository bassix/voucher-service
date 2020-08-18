#!/bin/bash

docker exec --user www-data app bin/console doctrine:database:drop --force
docker exec --user www-data app bin/console doctrine:database:create
docker exec --user www-data app bin/phpunit
