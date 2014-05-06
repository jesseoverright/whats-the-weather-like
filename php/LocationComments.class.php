<?php

class LocationComments {
    private $comments = array();
    private $location;

    public function __construct( $location ) {

        $this->location = $location;

        // connect to database
        # $db = new mysqli("localhost", "t3", "A2ztvTm8FxJpGhZp", "t3");
        try {
            $db = new mysqli("localhost", "weather", "password", "weather_comments");
        } catch  (Exception $e) {
            echo 'Database is unavailable';
        }

        // load comments
        $statement = $db->prepare("SELECT comment, date FROM comments WHERE location = ? ORDER BY date DESC");        

        $statement->bind_param('s', $this->location);

        $statement->execute();

        $statement->bind_result($comment, $date);

        while ( $statement->fetch() ) {
            $this->comments[] = array(
                'comment' => $comment,
                'date' => date('F jS, Y', strtotime($date) ),
            );
        }

        $statement->close();
        $db->close();
    }

    public function allComments() {
        return $this->comments;
    }

    public static function addComment( $comment, $location ) {
        try {
            $db = new mysqli("localhost", "weather", "password", "weather_comments");
        } catch (Exception $e) {
            echo 'Database is unavailable';
        }

        $statement = $db->prepare("INSERT INTO comments (location, comment, date) VALUES (?, ?, NOW())");

        $statement->bind_param('ss', $location, $comment);

        if ( $statement->execute() ) {
            $statement->close();
            $db->close();

            return TRUE;
        }
    }

    public static function renderComment( $comment, $date = '') {
        if ( $date == '' ) {
            $date = date('F jS, Y');
        }
        ?>
        <div class="comment">
            <?= $comment ?>
            <div class="date-posted">Posted on <?= $date ?></div>
        </div>
        <?php
    }
}