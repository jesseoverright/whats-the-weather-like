# What's the Weather Like...?

* Online: [jesseoverright.com/weather](http://www.jesseoverright.com/weather/)
* Github: [github.com/jesseoverright/whats-the-weather-like/](https://github.com/jesseoverright/whats-the-weather-like/)

What's the Weather Like...? is a simple, responsive, single-page weather application that utilizes the [wunderground api](http://www.wunderground.com/weather/api/) to look up weather conditions and allow users to comment on conditions at a location. Locations can be in either city, state or zip code format. All data is returned via AJAX.

![Screenshot of What's the Weather Like...?](https://raw.githubusercontent.com/jesseoverright/whats-the-weather-like/master/screenshot.png)

## Application Details

### Weather Data
Location data is available via the REST API endpoints `api/weather/{state}/{city}/` or `api/weather/{zip}/`. The resulting weather data is returned as JSON and includes the current conditions and forecasts for the next few days for location or error messages if the requested location is not found as typed. `js/scripts.min.js` renders the html from the returned JSON and inserts it into the DOM.

### Comments
Comments are stored in a mysql database. Comments are retrieved using the REST api endpoints `api/comments/{state}/{city/}/` and stored with POST calls to `api/comments/`. The resulting JSON is inserted into the DOM using javascript.

## Requirements
Whats the Weather Like...? requires the LAMP stack. It was developed using:

* Ubuntu 14.04 LTS
* Apache 2.4
* MySQL 5.5.37
* PHP 5.5.9
* Laravel 4.2
* jQuery
* Sass & Compass
* GruntJS
* Vagrant

## Running Locally

### Using Vagrant
This project includes a Vagrantfile for creating a development environment. Your machine needs to have [VirtualBox](http://www.virtualbox.org) and [Vagrant](http://www.vagrantup.com) installed.

To build the development environment, clone the repo and run:

`vagrant up`

After Vagrant has created the development environment, What's the Weather Like...? will be available at [weather.dev](http://weather.dev).

### Manually
To manually install the app in a development or production environment, symlink the `public/` folder into your www directory. `app-config.php` should be in the parent directory of `app/` for the app to work correctly.

You will also need to create the database and include your wunderground api key in `app-config.php`.

#### Setting up the Database
Database settings for the project are located in `app/config/database.php` and can be updated to your desired database host, user, and database name. `app/database/migrations/` contains the table structure for the `comments` table and can be seeded using the laravel commands `php artisan migrate` and `php artisan db:seed`.

## Browser Compatibility

Whats the Weather Like...? has been tested on the following browsers:

* Chrome
* Safari
* IE9
* iOS
* Android Jelly Bean