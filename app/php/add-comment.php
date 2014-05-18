<?php

/**
 * add-comment.php accepts POST data and handles validation for adding new comments to a location
 */

include 'LocationComments.class.php';

// sanitize user data
$location = $_POST['city'];
$new_comment = array(
    'comment' => $_POST['comment'],
    'conditions' => $_POST['temp'] . "&deg; and " . $_POST['conditions']
);

// scrub any html or js from user input
$location = strip_tags ( $location );
$new_comment['conditions'] = strip_tags( $new_comment['conditions'] );
$new_comment['comment'] = htmlspecialchars( strip_tags( trim ( nl2br( $new_comment['comment']) ), '<br>' ) );

$comments = new LocationComments( $location );

// save to database
if ( $new_comment['comment'] != '' ) {
    if ( $comments->addComment( $new_comment ) ) {
        $comments->renderComment( $new_comment );
    }
}

