<?php

/**
 * add-comment.php accepts POST data and handles validation for adding new comments to a location
 */

include 'LocationComments.class.php';

// sanitize user data
$location = $_POST['city'];
$comment = $_POST['comment'];
$current_conditions = $_POST['temp'] . "&deg; and " . $_POST['conditions'];

// scrub any html or js from user input
$location = strip_tags ( $location );
$current_conditions = strip_tags( $current_conditions );
$comment = htmlspecialchars( strip_tags( trim ( nl2br( $comment) ), '<br>' ) );

// save to database
if ( $comment != '' ) {
    if ( LocationComments::addComment($comment, $location, $current_conditions ) ) {
        LocationComments::renderComment( $comment, '', $current_conditions );
    }
}

