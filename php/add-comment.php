<?php

/**
 * add-comment.php accepts POST data and handles validation for adding new comments to a location
 */

include 'LocationComments.class.php';

// sanitize user data
$location = $_POST['city'];
$comment = $_POST['comment'];

// scrub any html or js from user input
$comment = htmlspecialchars( strip_tags( trim ($comment ) ) );

// save to database
if ( LocationComments::addComment($comment, $location) ) {
    LocationComments::renderComment( $comment );
}

