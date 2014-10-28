#!/bin/sh

cd /vagrant/src/config/autoload
DIRECTORY=.
echo "Setting up configuration files"
for i in $DIRECTORY/*.php.dist; do
    echo "Creating config from $i"
    cp $i ${i%?????}
done