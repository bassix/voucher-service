#!/bin/bash

{
    # Add host specific settings...
    echo HOST_UID=`id -u`;
    echo HOST_GID=`id -g`;
    echo PHP_DATE_TIMEZONE=Europe/Berlin;
} > .env;
