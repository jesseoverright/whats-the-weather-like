<?php
// app/database/seeds/WeatherCommentsSeeder.php

class WeatherCommentsSeeder extends Seeder {
    public function run() {
        DB::table('comments')->delete();

        Comment::create( array(
            'location'   => 'Austin, TX',
            'comment'    => 'I love this weather.',
            'date'       => '2014-10-13 09:00:00',
            'conditions' => '74.9&deg; and Clear'
            )
        );
    }
}