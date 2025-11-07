#!/bin/bash

mkdir -p /tmp/pear/temp
cd /tmp/pear/temp

pecl bundle apcu
cd apcu
phpize
./configure
make

mkdir -p /home/site/ext
cp /tmp/pear/temp/apcu/modules/apcu.so /home/site/ext

# Add apcu.ini
echo "extension=/home/site/ext/apcu.so" > /usr/local/etc/php/conf.d/apcu.ini
