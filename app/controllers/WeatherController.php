<?php

class WeatherController extends \BaseController {
    /**
     * Api info
     * @var array
     */
    protected $settings;

    public function __construct() {

        $this->results = array();

        include '../app-config.php';

        $this->settings = $settings;

        $this->validation_error = "Your location was not recognized as entered. A location must be in city, state or zip code format.";

    }

    public function zip( $zip ) {
        if ( strlen($zip) != 5) 
            $this->results['errors'][] = $this->validation_error;
        else {

            $this->wunderground_api['conditions'] = "http://api.wunderground.com/api/" . $this->settings['wunderground-api-key'] . "/geolookup/conditions/q/$zip.json";
            $this->wunderground_api['forecast'] = "http://api.wunderground.com/api/" . $this->settings['wunderground-api-key'] . "/forecast/q/$zip.json";
        }

        $this->get_weatherunderground_results();

        return Response::json( $this->results );
    }

    /**
     * Gets weather by city & state.
     *
     * @return Response
     */
    public function city( $state, $city )
    {
            
        // validate city for wunderground, replacing spaces with underscores
        $city = str_replace( ' ', '_', trim( $city ) );

        // calidate state city wunderground
        $state = strtoupper( substr( trim( $state ), 0, 2 ) );

        $this->wunderground_api['conditions'] = "http://api.wunderground.com/api/" . $this->settings['wunderground-api-key'] . "/geolookup/conditions/q/$state/$city.json";

        $this->wunderground_api['forecast'] = "http://api.wunderground.com/api/" . $this->settings['wunderground-api-key'] . "/forecast/q/$state/$city.json";

        $this->get_weatherunderground_results();

        // return results as json object
        return Response::json( $this->results );
    }

    protected function get_weatherunderground_results() {
        // if api calls have been constructed and no errors have been triggered, make wunderground api call
        if ( ! empty( $this->wunderground_api ) && empty( $this->results['errors']) ) {
            
            // get current conditions
            $results_string = file_get_contents( $this->wunderground_api['conditions'] );
            $parsed_json = json_decode( $results_string );
            $current_conditions = $parsed_json->current_observation;

            if ( empty( $current_conditions ) ) {
                $this->results['errors'][] = "Your location does not exist as you have entered it. A location must be in city, state or zip code format";
            } else {
                $this->results = array (
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
            $results_string = file_get_contents( $this->wunderground_api['forecast'] );
            $parsed_json = json_decode( $results_string );
            $forecast = $parsed_json->forecast->txt_forecast->forecastday;

            $this->results['forecast'] = array (
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
    }

}
