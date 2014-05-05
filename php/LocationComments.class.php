<?php

class LocationComments {
    private $comments = array();
    private $location;

    public function __construct( $location ) {

        // testing
        $this->comments = array( 'too hot!', 'too cold!' );
    }

    public function allComments() {
        return $this->comments;
    }

    public static function addComment( $comment, $location ) {
        return TRUE;
    }

    public static function renderComment( $comment ) {
        ?>
        <div class="comment"><?= $comment ?></div>
        <?php
    }
}