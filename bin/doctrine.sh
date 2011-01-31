#!/bin/sh

cd `dirname $0`
export APPLICATION_ENV=development
php -f ./doctrine.php $*