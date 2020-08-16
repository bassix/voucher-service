#!/bin/bash

./docker-stop.sh
docker rm -v $(docker ps -f -q)
docker rmi -f $(docker images -q)
docker system prune --all --force --volumes
