#!/bin/sh

cd /vagrant/src
./composer.phar self-update
./composer.phar install
