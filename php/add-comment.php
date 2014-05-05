<?php

/**
 * add-comment.php accepts POST data and handles validation for adding new comments to a location
 */
include 'LocationComments.class.php';

// validate and sanitize
$location = $_POST['city'];
$comment = $_POST['comment'];

// save to database
if ( LocationComments::addComment($comment, $location) ) {
    LocationComments::renderComment( $comment );
}

