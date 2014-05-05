$(document).ready(function() {
    
    // process form data from location search
    $('#location-form').submit(function(e) {
        // prevent standard form submit
        e.preventDefault();

        // request weather for location
        $.post( 'php/get-weather.php' , { location: $('#location').val() }, function(data) {
            var weather = $.parseJSON(data);

            var html = '<h1 class="current-location">' + weather.location + '</h1>'; 
            html += '<div class="current-conditions"><div class="temperature">' + weather.temperature + '&deg;</div><ul class="details">';

            html += '<li>Feels like: ' + weather.feelslike + '&deg;</li><li>Humidity: ' + weather.relative_humidity + '</li><li>Wind Speed: ' + weather.wind_speed + '</li><li>Wind Chill: ' + weather.wind_chill + '</li><li>Heat Index: ' + weather.heat_index_string + '</li>';

            html += '</ul><div class="conditions">' + weather.weather + '</div></div>';

            html += '<div class="forecast"><h3>Today</h3>';
            html += '<p>' + weather.forecast.today + ' ' + weather.forecast.tonight + '</p></div>';
            html += '<div class="forecast"><h3>Tomorrow</h3>';
            html += '<p>' + weather.forecast.tomorrow + '</p></div>';
        
            html += '<div class="observation">' + weather.observation_time + ' from ' + weather.observation_location + '</div>';

            $('.weather').html( html );
            
            var city = weather.location;

            if ( city !== undefined ) {
                $.post( 'php/get-comments.php' , { city: city }, function(data) {
                    $('.comments').html(data);

                    // process form data from adding a comment 
                    $('#comment-form').submit(function(e) {
                        e.preventDefault();

                        $.post( 'php/add-comment.php' , { comment: $('#comment').val(), city: city }, function(data) {
                            $('#comment-form').after(data);
                        });
                    })
                });
            }
        });
    });
});