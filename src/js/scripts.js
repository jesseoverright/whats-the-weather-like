$(document).ready(function() {
    
    // process form data from location search
    $('#location-form').submit(function(e) {
        // prevent standard form submit
        e.preventDefault();

        // request weather details for location
        $.post( 'php/get-weather.php' , { location: $('#location').val() }, function(data) {
            var weather = $.parseJSON(data);

            // clear any previous error messages
            $('.error').remove();

            // clear out any previous weather data
            $('.weather, .comments').fadeOut().empty();

            // validate user input is not html, will be sanitized later
            if (/<[a-z][\s\S]*>/i.test( $('#location').val() ) ) {
                $('<p class="error">HTML characters are not allowed and will be removed.</p>').appendTo('#location-form').hide().fadeIn();
            }

            // display error messages
            if ( weather.errors !== undefined ) {
                weather.errors.forEach(function (value) {
                    $('<p class="error">' + value + '</p>').appendTo('#location-form').hide().fadeIn();
                })

                return false;
            }
            else {
            }

            // generate the html for weather based on returned json
            var html = '<h1 class="current-location">' + weather.location + '</h1>'; 
            html += '<div class="current-conditions"><div class="temperature">' + weather.temperature + '&deg;</div><ul class="details">';

            html += '<li>Feels like: ' + weather.feelslike + '&deg;</li><li>Humidity: ' + weather.relative_humidity + '</li><li>Wind Speed: ' + weather.wind_speed + '</li>';
            if ( weather.wind_chill != 'NA' ) html += '<li>Wind Chill: ' + weather.wind_chill + '</li>';
            if ( weather.heat_index_string != 'NA' ) html += '<li>Heat Index: ' + weather.heat_index_string + '</li>';

            html += '</ul><div class="conditions">' + weather.weather + '<img src="' + weather.icon + '" alt="' + weather.icon_alt + '"></div></div>';

            html += '<div class="forecast"><img src="' + weather.forecast.today.icon + '" alt="' + weather.forecast.today.icon_alt + '"><h3>Today</h3>';
            html += '<p>' + weather.forecast.today.text + '</p></div>';
            html += '<div class="forecast"><img src="' + weather.forecast.tonight.icon + '" alt="' + weather.forecast.tonight.icon_alt + '"><h3>Tonight</h3>';
            html += '<p>' + weather.forecast.tonight.text + '</p></div>';
            html += '<div class="forecast"><img src="' + weather.forecast.tomorrow.icon + '" alt="' + weather.forecast.tomorrow.icon_alt + '"><h3>Tomorrow</h3>';
            html += '<p>' + weather.forecast.tomorrow.text + '</p></div>';
        
            html += '<div class="observation">' + weather.observation_time + ' from ' + weather.observation_location + '</div>';

            $('.weather').hide().html( html ).fadeIn();
            
            var city = weather.location;

            // load up comments for valid locations
            if ( city !== undefined ) {
                $.post( 'php/get-comments.php' , { city: city }, function(data) {


                    $('.comments').hide().html( data ).fadeIn();

                    // add event to process form data from adding a new comment 
                    $('#comment-form').submit(function(e) {
                        e.preventDefault();

                        $.post( 'php/add-comment.php' , { comment: $('#comment').val(), city: city }, function(data) {
                            // clear any previous error messages
                            $('#comment-form .error').remove();

                            // validate user input is not html, will be sanitized later
                            if (/<[a-z][\s\S]*>/i.test( $('#comment').val() ) ) {
                                $('<p class="error">HTML characters are not allowed and will be removed.</p>').insertAfter($('#comment')).hide().fadeIn();
                                console.log('html found');
                            }

                            // clear out comment
                            $('#comment').val('');
                            $(data).insertAfter('#comment-form').hide().fadeIn();
                            //$('#comment-form').after(data).hide().fadeIn();
                        });
                    })
                });
            } else {
                $('.comments').fadeOut().empty();
            }
        });
    });
});