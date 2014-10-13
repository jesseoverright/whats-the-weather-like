<?php

// app/models/Comment.php

class Comment extends Eloquent {
    protected $fillable = array( 'location', 'comment', 'date', 'conditions' );
}