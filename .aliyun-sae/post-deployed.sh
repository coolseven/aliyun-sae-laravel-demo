#!/usr/bin/env sh

set -eux

php /app/artisan optimize > /app/storage/logs/deployed.log
