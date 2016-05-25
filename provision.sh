#!/bin/bash

apt-get update

apt-get install -y fish tree

sudo -H -u vagrant bash -c "/home/vagrant/.rbenv/shims/gem install dotenv"
sudo -H -u vagrant bash -c "/home/vagrant/.rbenv/shims/gem install sass"
wget -qNO /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit
echo "Installed $(phpunit --version)"

apt-get install -y language-pack-DE

if [ ! -f /home/vagrant/provisioned ]; then
  wget --progress=bar:force -P /tmp/ http://builds.piwik.org/piwik.zip
  unzip /tmp/piwik.zip -d /var/www
  chown -R vagrant:vagrant /var/www/piwik
  sed -i -e 's/;always_populate_raw_post_data/always_populate_raw_post_data/g' /etc/php5/apache2/php.ini
  sed -i '6iListen 8080' /etc/apache2/ports.conf
  # Add /var/piwik as apache directory (?)
  cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/001-piwik.conf
  sed -i -e "s#DocumentRoot /var/www/public#DocumentRoot /var/www/gsa/public#g" /etc/apache2/sites-available/000-default.conf
  sed -i -e "s#DocumentRoot /var/www/public#DocumentRoot /var/www/gsa/public#g" /etc/apache2/sites-available/scotchbox.local.conf
  sed -i -e "s#DocumentRoot /var/www/public#DocumentRoot /var/www/piwik#g" /etc/apache2/sites-available/001-piwik.conf
  sed -i -e "s/<VirtualHost \*:80>/<VirtualHost \*:8080>/g" /etc/apache2/sites-available/001-piwik.conf
  a2ensite 001-piwik.conf
  service apache2 restart
  mysql -uroot -proot -e "create database piwik"
  touch /home/vagrant/provisioned
  echo "Visit /piwik to complete the installation"
fi
