#!/bin/sh

# The -d flag will import schema-data.sql instead of schema.sql

vagrant ssh -c"mysql -uroot -p123 -e 'DROP DATABASE IF EXISTS lamarche; CREATE DATABASE lamarche;'"
if [ "$1" = "-d" ]; then
    echo "Importing data..."
    vagrant ssh -c"mysql -uroot -p123 lamarche < /vagrant/sql/schema-data.sql"
else
    echo "Importing schema..."
    vagrant ssh -c"mysql -uroot -p123 lamarche < /vagrant/sql/schema.sql"
fi
