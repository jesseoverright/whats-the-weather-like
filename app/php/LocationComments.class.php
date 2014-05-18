<?php

/**
 * LocationComments class handles all database interactions related to comments
 */

include '../../app-config.php';

class LocationComments {
    /**
     * database connection
     * @var mysqli
     */
    private $db;

    /**
     * multidimensional array of comments (comment, date) from this location
     * @var array 
     */
    private $comments = array();

    /**
     * current location comments are related to
     * @var string
     */
    private $location;

    /**
     * Constructor method
     * queries the database for comments on provided location
     * @param string $location
     */
    public function __construct( $location ) {

        $this->location = $location;

        global $settings;

        // connect to database
        try {
            $this->db = new mysqli( $settings['dbconfig']['host'], $settings['dbconfig']['user'], $settings['dbconfig']['password'], $settings['dbconfig']['database']);
        } catch  (Exception $e) {
            echo 'Database is unavailable';
        }

    }

    /**
     * Destructor method
     * closes database connection
     */
    public function __destruct() {
        $this->db->close();
    }

    /**
     * Returns multidimensional array of current comments
     * @return array in format comment['comment'], comment['date'], comment['conditions']
     */
    public function allComments() {

        // load comments
        $statement = $this->db->prepare("SELECT comment, date, conditions FROM comments WHERE location = ? ORDER BY date DESC");

        $statement->bind_param('s', $this->location);

        $statement->execute();

        $statement->bind_result($comment, $date, $conditions);

        while ( $statement->fetch() ) {
            $this->comments[] = array(
                'comment' => $comment,
                'date' => date('F jS, Y', strtotime($date) ),
                'conditions' => $conditions,
            );
        }

        // clean up database connection
        $statement->close();

        return $this->comments;
    }

    /**
     * static function to insert comment into the database 
     * @param array $comment  in format comment['comment'], comment['date'], comment['conditions']
     * @return   success status of insert
     */
    public function addComment( $comment ) {

        $statement = $this->db->prepare("INSERT INTO comments (location, comment, date, conditions) VALUES (?, ?, NOW(), ?)");

        $statement->bind_param('sss', $this->location, $comment['comment'], $comment['conditions'] );

        if ( $statement->execute() ) {
            $statement->close();

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Returns html snippet of comment
     * @param  array $comment in format comment['comment'], comment['date'], comment['conditions']
     * @return html          html snippet
     */
    public function renderComment( $comment = array()) {
        if ( $comment['date'] == '' ) {
            $comment['date'] = date('F jS, Y');
        }
        ?>
        <div class="comment">
            <p><?php echo htmlspecialchars_decode($comment['comment']) ?></p>
            <div class="date-posted">
                On <?= $comment['date'] ?><br>
                <?= $comment['conditions'] ?>
            </div>
        </div>
        <?php
    }
}