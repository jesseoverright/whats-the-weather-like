#!/usr/bin/env bash

# set mysql root password
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password rootpass'
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password rootpass'

# install apache, mysql, php
apt-get update
apt-get install -y apache2 mysql-server-5.5 php5-mysql php5

# symlink DocRoot to vagrant
rm -rf /var/www
ln -fs /vagrant/app /var/www

# set up the database
if [ ! -f /var/log/databasesetup ];
then
    echo "CREATE USER 'weather'@'localhost' IDENTIFIED BY 'password'" | mysql -uroot -prootpass
    echo "CREATE DATABASE weather_comments" | mysql -uroot -prootpass
    echo "GRANT ALL ON weather_comments.* TO 'weather'@'localhost'" | mysql -uroot -prootpass
    echo "flush privileges" | mysql -uroot -prootpass

    touch /var/log/databasesetup

    if [ -f /vagrant/src/comments_db.sql ];
    then
        mysql -uroot -prootpass weather_comments < /vagrant/src/comments_db.sql
    fi
fi

# restart apache
sudo service apache2 restart