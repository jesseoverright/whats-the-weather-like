# What's the Weather Like...?

* Online: [jesseoverright.com/weather](http://www.jesseoverright.com/weather/)
* Github: [github.com/jesseoverright/whats-the-weather-like/](https://github.com/jesseoverright/whats-the-weather-like/)

What's the Weather Like...? is a simple, responsive, single-page weather application that utilizes the [wunderground api](http://www.wunderground.com/weather/api/) to look up weather conditions and allow users to comment on conditions at a location. Locations can be in either city, state or zip code format. All data is returned via AJAX.

![Screenshot of What's the Weather Like...?](https://raw.githubusercontent.com/jesseoverright/whats-the-weather-like/master/screenshot.png)

## Application Details

### Weather Data
Location data is validated and scrubbed in `php/get-weather.php`. The resulting weather data is returned as JSON and includes the current conditions and forecasts for the next few days for location or error messages if the requested location is not found as typed. `js/scripts.min.js` renders the html from the returned JSON and inserts it into the DOM.

### Comments
Comments are stored in a mysql database. All database interactions are handled in the php class `php/LocationComments.class.php`. `get-comments.php` and `add-comment.php` are php scripts that run via AJAX when a user adds a comment or looks up the weather at a location. The resulting html is inserted into the DOM using javascript.

### Key Files

* `index.html` - main landing page
* `css/main.css` - compiled css file from scss
* `js/scripts.min.js` - minified javascript file for handling ajax requests, rendering weather data, and adding/displaying comments
* `php/LocationComments.class.php` - Handles all database interactions and html rendering related to comments
* `php/get-weather.php` - returns JSON data based on location
* `php/get-comments.php` - generates comment form and comments related to location
* `php/add-comment.php` - handles comment validation and saving via `LocationComments.class.php`


## Requirements
Whats the Weather Like...? requires the LAMP stack. It was developed using:

* Ubuntu 12.04.4 LTS
* Apache 2.2.22
* MySQL 5.5.37
* PHP 5.3.10
* jQuery
* Sass & Compass
* GruntJS
* Vagrant

## Running Locally

### Using Vagrant
This project includes a Vagrantfile for creating a development environment. Your machine needs to have [VirtualBox](http://www.virtualbox.org) and [Vagrant](http://www.vagrantup.com) installed.

To build the development environment, clone the repo and run:

`vagrant up`

After Vagrant has created the development environment, What's the Weather Like...? will be available at [localhost:1234](http://localhost:1234).

### Manually
To manually install the app in a development or production environment, symlink the `app/` folder into your www directory. `app-settings.php` should be in the parent directory of `app/` for the app to work correctly.

You will also need to create the database and include your wunderground api key in `app-settings.php`.

#### Setting up the Database
Database settings for the project are located in `app-settings.php` and can be updated to your desired database host, user, and database name. `comments_db.sql` contains the table structure for the `comments` table and should be run as sql or imported into the newly created database.

## Browser Compatibility

Whats the Weather Like...? has been tested on the following browsers:

* Chrome
* Safari
* IE9
* iOS
* Android Jelly Bean