<?php

/**
 * LocationComments class handles all database interactions related to comments
 */

include '../../app-config.php';

class LocationComments {
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
            $db = new mysqli( $settings['dbconfig']['host'], $settings['dbconfig']['user'], $settings['dbconfig']['password'], $settings['dbconfig']['database']);
        } catch  (Exception $e) {
            echo 'Database is unavailable';
        }

        // load comments
        $statement = $db->prepare("SELECT comment, date, conditions FROM comments WHERE location = ? ORDER BY date DESC");        

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
        $db->close();
    }

    /**
     * Returns multidimensional array of current comments
     * @return array in format comment['comment'], comment['date']
     */
    public function allComments() {
        return $this->comments;
    }

    /**
     * static function to insert comment into the database 
     * @param string $comment  text of the comment to save
     * @param string $location location
     * @return   success status of insert
     */
    public static function addComment( $comment, $location, $conditions ) {
        global $settings;
        
        try {
            $db = new mysqli( $settings['dbconfig']['host'], $settings['dbconfig']['user'], $settings['dbconfig']['password'], $settings['dbconfig']['database']);
        } catch (Exception $e) {
            echo 'Database is unavailable';
        }

        $statement = $db->prepare("INSERT INTO comments (location, comment, date, conditions) VALUES (?, ?, NOW(), ?)");

        $statement->bind_param('sss', $location, $comment, $conditions);

        if ( $statement->execute() ) {
            $statement->close();
            $db->close();

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Returns html snippet of comment
     * @param  string $comment comment to render
     * @param  string $date    date of comment, or blank for today
     * @return html          html snippet
     */
    public static function renderComment( $comment, $date = '', $conditions = '') {
        if ( $date == '' ) {
            $date = date('F jS, Y');
        }
        ?>
        <div class="comment">
            <p><?php echo htmlspecialchars_decode($comment) ?></p>
            <div class="date-posted">
                On <?= $date ?><br>
                <?= $conditions ?>
            </div>
        </div>
        <?php
    }
}