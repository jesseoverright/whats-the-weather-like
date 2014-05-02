$(document).ready(function() {
    
    $('form').submit(function(e) {
        // prevent standard form submit
        e.preventDefault();

        $.post('php/get-weather.php', { location: $('#location').val() }, function(data) {
            $('.weather').html(data);
            console.log(data);
        });
    });
});