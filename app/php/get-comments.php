<?php

/**
 * get-comments.php loads current comments from the database based on location
 */

// get location details from wunderground
$location = $_POST['city'];
echo "<h2>Comments about " . $location . "</h2>";

// get coments from database
include 'LocationComments.class.php';
$comments = new LocationComments( $location );

?>

<form id="comment-form" method="POST">

    <label for="comment">How does this make you feel?</label>
    <textarea id="comment" name="comment" placeholder="Write your comment about the weather at this location."></textarea>
    <button type="submit">Comment</button>

</form>

<?php

// return comments

foreach ( $comments->allComments() as $comment ) {
    $comments->renderComment( $comment['comment'], $comment['date'] );
}