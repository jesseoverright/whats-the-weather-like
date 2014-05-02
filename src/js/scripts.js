$(document).ready(function() {
    
    // process form data from location search
    $('#location-form').submit(function(e) {
        // prevent standard form submit
        e.preventDefault();

        // request weather for location
        $.post( 'php/get-weather.php' , { location: $('#location').val() }, function(data) {
            $('.weather').html(data);
            
            var city = $.parseJSON(data).location;

            $.post( 'php/get-comments.php' , { city: city }, function(data) {
                $('.comments').html(data);
            });
        });
    });

    // process form data from adding a comment 
    $('#comment-form').submit(function(e) {
        e.preventDefault();

        $.post( 'php/add-comment.php' , { comment: $('#comment').val(), city: 'stuff' }, function(data) {
            console.log('add a comment now');
        });
    })
});