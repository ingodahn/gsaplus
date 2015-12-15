#!/bin/bash

apt-get -y update

apt-get install -y mongodb ruby-dev zlib1g-dev libsqlite3-dev nodejs build-essential libxml2 libxml2-dev libxslt1-dev nodejs nodejs-legacy npm git fish
gem install bundler
gem install rails
npm install -g bower

su vagrant
update-locale LANG=en_US.UTF-8 LANGUAGE=en_US.UTF-8 LC_ALL=en_US.UTF-8
cd /vagrant
bundle install
bower install
