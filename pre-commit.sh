#!/bin/sh
DIR=$(git rev-parse --show-toplevel)
cd "$DIR"
echo "Cleaning whitespace and verifying PSR0/1 compatibility"
php-cs-fixer fix ./src/module/Application/src/Application/
STYLE=$?
cd "$DIR/src"
echo "Updating composer autoloader for page load performance"
composer install --optimize-autoloader
COMPOSER=$?
echo "Generating doctrine proxies"
vagrant ssh -c"cd /vagrant/src;php vendor/bin/doctrine orm:generate-proxies"
DOCTRINE=$?
cd "$DIR"
echo "Generating ZF2 Classmap for page load performance"
php ./src/vendor/bin/classmap_generator.php -w -l src/module/Application
if [ $? -ne 0 ] || [ $STYLE -ne 0 ] || [ $DOCTRINE -ne 0 ] || [ $COMPOSER -ne 0 ] ; then
    echo "Pre-commit hook generated more to do. You can bypass this if you run 'git commit --no-verify' but why would you do something like that?"
    exit 1
fi