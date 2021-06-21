#!/bin/bash
# Laravel boilerplate githook script

# PHP CodeSniffer
./vendor/bin/phpcs --config-set colors 1 >> /dev/null
./vendor/bin/phpcs --config-set show_progress 1 >> /dev/null
./vendor/bin/phpcs --config-set show_warnings 1 >> /dev/null
./vendor/bin/phpcs app/* -n --runtime-set ignore_warnings_on_exit true --standard=PSR12 --ignore=app/providers/*
