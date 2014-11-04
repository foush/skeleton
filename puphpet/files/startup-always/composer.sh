#!/bin/sh

cd /vagrant/src
curl -sS https://getcomposer.org/installer | php
php composer.phar self-update
php composer.phar install
