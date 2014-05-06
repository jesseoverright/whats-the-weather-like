<?php

/**
 * src/settings.php contains login information including database settings api keys
 */


//$db = new mysqli("localhost", "t3", "A2ztvTm8FxJpGhZp", "t3");


$settings = array (
    'dbconfig' => array (
        'database' => 'weather_comments',
        'user' => 'weather',
        'password' => 'password',
    ),

    'wunderground-api-key' => 'b783d7a35bf36bb2',
);