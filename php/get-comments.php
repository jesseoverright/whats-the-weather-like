<?php

/**
 * get-comments.php loads current comments from the database based on location
 */

// get location details
?>
<pre>
<?php print_r($_POST); ?>
</pre>
<?php
$location = $_POST['city'];
echo "<h2>Comments about " . $location . "</h2>";

// connect to database

// return comments


?>
<form id="comment-form" method="POST">

    <label for="comment">How does this make you feel?</label>
    <textarea id="comment" name="comment" placeholder="Write your comment about the weather at this location here."></textarea>
    <button type="submit">Comment</button>

</form>