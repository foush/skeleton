#!/bin/sh
webuser=lamarcheas-01
sudo -u $webuser cp -prvf /WebSites/$webuser/config/autoload/doctrine.local.php.dist-$1 /WebSites/$webuser/config/autoload/doctrine.local.php
sudo -u $webuser cp -prvf /WebSites/$webuser/config/autoload/aws.local.php.dist-$1 /WebSites/$webuser/config/autoload/aws.local.php
sudo -u $webuser cp -prvf /WebSites/$webuser/config/autoload/aws_zf2.local.php.dist-$1 /WebSites/$webuser/config/autoload/aws_zf2.local.php
echo -e $webuser >> /tmp/deploy-script-ran
