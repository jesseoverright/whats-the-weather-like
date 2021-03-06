$(document).ready(function() {
    
    // process form data from location search
    $('#location-form').submit(function(e) {
        // prevent standard form submit
        e.preventDefault();

        var location = $('#location').val().split(',');
        var route = '/api/weather/';
        if ( location.length == 1 ) {
            route += location[0].trim() + '/';
        } else {
            route += location[1].trim() + '/' + location[0].trim() + '/';
        }

        // request weather details for location
        $.get( route , {}, function(weather) {

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

            html += '<li><strong>Feels like:</strong> ' + weather.feelslike + '&deg;</li><li><strong>Humidity:</strong> ' + weather.relative_humidity + '</li><li><strong>Wind Speed:</strong> ' + weather.wind_speed + '</li>';
            if ( weather.wind_chill != 'NA' ) html += '<li><strong>Wind Chill:</strong> ' + weather.wind_chill + '</li>';
            if ( weather.heat_index_string != 'NA' ) html += '<li><strong>Heat Index:</strong> ' + weather.heat_index_string + '</li>';

            html += '</ul><div class="conditions">' + weather.weather + '<img src="' + weather.icon + '" alt="' + weather.icon_alt + '"></div></div>';

            html += '<div class="forecast"><h3>Today</h3>';
            html += '<p><img src="' + weather.forecast.today.icon + '" alt="' + weather.forecast.today.icon_alt + '">' + weather.forecast.today.text + '</p></div>';
            html += '<div class="forecast"><h3>Tonight</h3>';
            html += '<p><img src="' + weather.forecast.tonight.icon + '" alt="' + weather.forecast.tonight.icon_alt + '">' + weather.forecast.tonight.text + '</p></div>';
            html += '<div class="forecast"><h3>Tomorrow</h3>';
            html += '<p><img src="' + weather.forecast.tomorrow.icon + '" alt="' + weather.forecast.tomorrow.icon_alt + '">' + weather.forecast.tomorrow.text + '</p></div>';
        
            html += '<div class="observation">' + weather.observation_time + ' from ' + weather.observation_location + '</div>';

            $('.weather').hide().html( html ).fadeIn();
            
            var city = weather.location;
            var temp = weather.temperature;
            var conditions = weather.weather;

            // load up comments for valid locations
            if ( city !== undefined ) {
                var location = city.split(',');
                var route = '/api/comments/' + location[1].trim() + '/' + location[0].trim() + '/';

                $.post( route , {}, function(comments) {
                    var html = '<h2>Comments about ' + location + '</h2>';

                    html += '<form id="comment-form" method="POST">';

                    html += '<label for="comment">How does this make you feel?</label>';
                    html += '<textarea id="comment" name="comment" placeholder="Write your comment about the weather at this location."></textarea>';
                    html += '<button type="submit">Comment</button>';
                    html += '</form>';

                    comments.forEach( function(comment) {
                        html += '<div class="comment">
                            <p>' + comment.comment + '</p>
                            <div class="date-posted">
                                On ' + comment.date + '<br>
                                ' + comment.conditions + '
                            </div>
                        </div>';
                    });

                    $('.comments').hide().html( html ).fadeIn();

                    // add event to process form data from adding a new comment 
                    $('#comment-form').submit(function(e) {
                        e.preventDefault();

                        $.post( 'api/comments/add' , { comment: $('#comment').val(), city: city, temp: temp, conditions: conditions }, function(data) {
                            // clear any previous error messages
                            $('#comment-form .error').remove();

                            if ( data.errors !== undefined ) {
                                data.errors.forEach(function (value) {
                                    $('<p class="error">' + value + '</p>').insertAfter($('#comment')).hide().fadeIn();
                                });
                            }

                            // clear out comment
                            $('#comment').val('');

                            if ( data.success ) {
                                var comment = '<div class="comment">
                                    <p>' + data.content + '</p>
                                    <div class="date-posted">
                                        On ' + data.date + '<br>
                                        ' + data.conditions + '
                                    </div>
                                </div>';
                                $(comment).insertAfter('#comment-form').hide().fadeIn();
                            }
                        });
                    })
                });
            } else {
                $('.comments').fadeOut().empty();
            }
        });
    });
});