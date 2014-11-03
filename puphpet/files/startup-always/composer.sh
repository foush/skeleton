#!/bin/sh

cd /vagrant/src
php composer.phar self-update
php composer.phar install
