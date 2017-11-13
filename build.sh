#!/usr/bin/env bash

__DIR__="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# required libs
source "${__DIR__}/.bash/functions.shlib"

set -E
trap 'throw_exception' ERR

php_version="$(php --version | head -n1 | cut -d' ' -f2)"

consolelog 'composer install'
composer install \
  --no-interaction \
  --prefer-dist \
  --no-suggest \
  --quiet \
  --verbose

consolelog 'install phpunit'
# switch phpunit version depending on php version
if [[ "${php_version}" == 7.* ]]; then
  composer require \
    --dev \
    --update-with-dependencies \
    --quiet \
    --verbose \
    phpunit/phpunit
elif [[ "${php_version}" == 5.6.* ]]; then
  composer require \
    --dev \
    --update-with-dependencies \
    --quiet \
    --verbose \
    phpunit/phpunit '5.7.*'
else
  composer require \
    --dev \
    --update-with-dependencies \
    --quiet \
    --verbose \
    phpunit/phpunit '4.8.*'
fi

composer dump-autoload \
  --classmap-authoritative \
  --quiet \
  --verbose

if [[ ! -z "${RUN_COVERAGE}" ]]; then
  consolelog 'run tests & coverage'

  mkdir -p build/logs
  vendor/bin/phpunit --coverage-text --coverage-clover build/logs/clover.xml
  vendor/bin/coveralls --quiet
else
  consolelog 'run tests'
  vendor/bin/phpunit
fi

consolelog 'composer optimise'
composer remove \
  --dev \
  --quiet \
  --verbose \
  phpunit/phpunit

composer install \
  --no-dev \
  --quiet \
  --verbose

composer dump-autoload \
  --no-dev \
  --classmap-authoritative \
  --quiet \
  --verbose
