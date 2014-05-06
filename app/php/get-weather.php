<?php

/**
 * get-weather.php accepts POST data and handles data validation as well as calling the wunderground api
 */

$json = array();


include '../../app-config.php';

$validation_error = "Your location was not recognized as entered. A location must be in city, state or zip code format.";

$location = $_POST['location'];
// check if location has been entered as a zip code
if ( is_numeric ( $location ) ) {
    if ( strlen($location) != 5) 
        $json['errors'][] = $validation_error;
    else {
        $zip = $location;

        $wunderground_api['conditions'] = "http://api.wunderground.com/api/" . $settings['wunderground-api-key'] . "/geolookup/conditions/q/$zip.json";
        $wunderground_api['forecast'] = "http://api.wunderground.com/api/" . $settings['wunderground-api-key'] . "/forecast/q/$zip.json";
    }
} else {
    // isloate city from state using comma as delimiter
    $location = explode(',', $_POST['location']);

    if ( count($location) != 2 ) {
        $json['errors'][] = $validation_error;
    } else {
        // validate city for wunderground, replacing spaces with underscores
        $city = str_replace( ' ', '_', trim( $location[0] ) );

        // calidate state city wunderground
        $state = strtoupper( substr( trim( $location[1] ), 0, 2 ) );

        $wunderground_api['conditions'] = "http://api.wunderground.com/api/" . $settings['wunderground-api-key'] . "/geolookup/conditions/q/$state/$city.json";

        $wunderground_api['forecast'] = "http://api.wunderground.com/api/" . $settings['wunderground-api-key'] . "/forecast/q/$state/$city.json";
    }

}

// if api calls have been constructed and no errors have been triggered, make wunderground api call
if ( ! empty( $wunderground_api ) && empty($json['errors']) ) {
    
    // get current conditions
    $json_string = file_get_contents( $wunderground_api['conditions'] );
    $parsed_json = json_decode( $json_string );
    $current_conditions = $parsed_json->current_observation;

    if ( empty( $current_conditions ) ) {
        $json['errors'][] = "Your location does not exist as you have entered it. A location must be in city, state or zip code format";
    } else {
        $json = array (
            'location' => $current_conditions->display_location->full,
            'observation_location' => $current_conditions->observation_location->full,
            'observation_time' => $current_conditions->observation_time,
            'weather' => $current_conditions->weather,
            'temperature' => $current_conditions->temp_f,
            'relative_humidity' => $current_conditions->relative_humidity,
            'wind_speed' => $current_conditions->wind_string,
            'feelslike' => $current_conditions->feelslike_f,
            'wind_chill' => $current_conditions->windchill_string,
            'UV' => $current_conditions->UV,
            'heat_index_string' => $current_conditions->heat_index_string,
            'icon' => $current_conditions->icon_url,
            'icon_alt' => $current_conditions->icon,
        );
    }

    // get forecast
    $json_string = file_get_contents( $wunderground_api['forecast'] );
    $parsed_json = json_decode( $json_string );
    $forecast = $parsed_json->forecast->txt_forecast->forecastday;

    $json['forecast'] = array (
        'today' => array (
            'text' => $forecast[0]->fcttext,
            'icon' => $forecast[0]->icon_url,
            'icon_alt' => $forecast[0]->icon,
        ),
        'tonight' => array (
            'text' => $forecast[1]->fcttext,
            'icon' => $forecast[1]->icon_url,
            'icon_alt' => $forecast[1]->icon,
        ),
        'tomorrow' => array(
            'text' => $forecast[2]->fcttext,
            'icon' => $forecast[2]->icon_url,
            'icon_alt' => $forecast[2]->icon,
        ),
    );
}

// return results as json object
echo json_encode($json);