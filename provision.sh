#!/bin/bash

apt-get update

apt-get install -y fish tree
/home/vagrant/.rbenv/shims/gem install sass
wget -qNO /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit
echo "Installed $(phpunit --version)"

sudo apt-get install language-pack-DE -y

wget -P /tmp/ http://builds.piwik.org/piwik.zip
unzip /tmp/piwik.zip -d /tmp
mv /tmp/piwik /var/www/public/piwik
echo "always_populate_raw_post_data = -1" >> /etc/php5/apache2/php.ini
sudo service apache2 restart
mysql -uroot -proot -e "create database piwik"
echo "Visit /piwik to complete the installation"
