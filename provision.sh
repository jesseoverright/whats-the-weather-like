#! /usr/bin/env bash

# variables for mysql database & server
MYSQL_ROOT_PASSWORD=rootpass
DATABASE_USER=weather
DATABASE_PASSWORD=password
DATABASE_NAME=weather_comments
SERVERNAME=weather.dev

# set mysql root password
echo "mysql-server-5.5 mysql-server/root_password password $MYSQL_ROOT_PASSWORD" | debconf-set-selections
echo "mysql-server-5.5 mysql-server/root_password_again password $MYSQL_ROOT_PASSWORD" | debconf-set-selections

# install apache, mysql, php
apt-get update
apt-get install -y apache2 mysql-server-5.5 php5-mysql php5 php5-mcrypt git curl

# configure Apache
if [ ! -f /var/log/apachesetup ];
then
    # add www-data to vagrant group
    usermod -a -G vagrant www-data

    # create vhost
    cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/$SERVERNAME.conf
    a2dissite 000-default
    a2ensite $SERVERNAME
    
    # enable mod rewrite
    a2enmod rewrite
    sed -i 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\n        <Directory "\/var\/www\/html">\n            AllowOverride All\n        <\/Directory>/' /etc/apache2/sites-available/$SERVERNAME.conf

    # configure ServerName
    echo "ServerName $SERVERNAME" >> /etc/apache2/conf-available/servername.conf
    a2enconf servername

    sed -i "s/#ServerName www.example.com/ServerName $SERVERNAME/" /etc/apache2/sites-available/$SERVERNAME.conf

    touch /var/log/apachesetup
fi

# configure php
if [ ! -f /var/log/phpsetup ];
then

    # enable mcrypt
    php5enmod mcrypt

    # increase file upload limits
    sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/g' /etc/php5/apache2/php.ini
    sed -i 's/post_max_size = .*/post_max_size = 20M/g' /etc/php5/apache2/php.ini
    
    # turn on error reporting
    sed -i 's/error_reporting = .*/error_reporting = E_ALL/' /etc/php5/apache2/php.ini
    sed -i 's/display_errors = .*/display_errors = On/' /etc/php5/apache2/php.ini

    # install composer
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer

    touch /var/log/phpsetup
fi

# set up the database
if [ ! -f /var/log/databasesetup ];
then
    mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "CREATE USER '$DATABASE_USER'@'localhost' IDENTIFIED BY '$DATABASE_PASSWORD'"
    mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "CREATE DATABASE $DATABASE_NAME"
    mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "GRANT ALL ON $DATABASE_NAME.* TO '$DATABASE_USER'@'localhost'"
    mysql -uroot -p$MYSQL_ROOT_PASSWORD -e "flush privileges"

    touch /var/log/databasesetup

fi

# configure phpmyadmin
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password $MYSQL_ROOT_PASSWORD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password $MYSQL_ROOT_PASSWORD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password $MYSQL_ROOT_PASSWORD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections

export DEBIAN_FRONTEND=noninteractive
apt-get install -y phpmyadmin 


# symlink www folder
rm -rf /var/www/html
ln -fs /vagrant/public /var/www/html

# setup laravel and database
cd /vagrant
composer install
php artisan migrate
php artisan db:seed


# restart apache
sudo service apache2 restart