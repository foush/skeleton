#!/bin/sh
mysql -uroot -p123 -e 'create database votr;'
mysql -uroot -p123 votr < /vagrant/sql/schema.sql