#!/bin/bash

apt-get install -y fish tree
/home/vagrant/.rbenv/shims/gem install sass
wget -qNO /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit
echo "Installed $(phpunit --version)"

sudo apt-get install language-pack-DE -y
