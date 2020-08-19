#!/bin/bash

sleep 5

while true;
do
    bin/console messenger:consume --time-limit=55 --memory-limit=128M >&1
    sleep 56
done
