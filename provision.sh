#!/bin/bash

apt-get update

apt-get install -y fish tree

apt-get install -y ruby-dotenv ruby-sass
wget -qNO /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit
echo "Installed $(phpunit --version)"

sudo apt-get install language-pack-DE -y

if [ ! -d "/var/www/public/piwik" ]; then
  wget --progress=bar:force -P /tmp/ http://builds.piwik.org/piwik.zip
  unzip /tmp/piwik.zip -d /tmp
  mv /tmp/piwik /var/www/public/
  sed -i -e 's/;always_populate_raw_post_data/always_populate_raw_post_data/g' /etc/php5/apache2/php.ini
  sudo service apache2 restart
  mysql -uroot -proot -e "create database piwik"
  echo "Visit /piwik to complete the installation"
fi
