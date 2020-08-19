#!/bin/bash

sleep 5

while true;
do
    bin/console messenger:consume --time-limit=295 --memory-limit=128M >&1
    sleep 295
done
