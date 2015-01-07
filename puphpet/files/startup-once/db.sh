#!/bin/sh
mysql -uroot -p123 -e 'create database fzyskeleton;'
mysql -uroot -p123 fzyskeleton < /vagrant/sql/schema.sql