#!/bin/bash
# Laravel boilerplate githook script

# Sensiolabs Security Checker
./vendor/bin/security-checker security:check composer.lock
