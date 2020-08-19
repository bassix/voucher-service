#!/bin/bash

{
    # Add host specific settings...
    echo HOST_UID=`id -u`;
    echo HOST_GID=`id -g`;
    echo PHP_DATE_TIMEZONE=Europe/Berlin;
    echo OPCACHE_VALIDATE_TIMESTAMPS=1;
} > .env;
