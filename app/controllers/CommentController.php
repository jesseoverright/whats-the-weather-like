<?php

class CommentController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function show( $state, $city ) 
	{
		$location = $city . ', ' . $state;
		$comments = Comment::where('location', '=', $city . ', ' . $state )->get();

		foreach ( $comments as &$comment ) {
			$date = new DateTime( $comment['date'] );
			$comment['date'] = $date->format('F jS, Y');
		}

		return Response::json( $comments );
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$response = array (
			'success' => false
		);

		$location = Input::get( 'city' );
		$comment['content'] = Input::get( 'comment' );
		$comment['date'] = new DateTime();
		$comment['conditions'] = Input::get( 'temp' ) . "&deg; and " . Input::get( 'conditions' );

		// scrub any html or js from user input
		$location = strip_tags ( $location );
		$comment['conditions'] = strip_tags( $comment['conditions'] );
		$comment['content'] = htmlspecialchars( strip_tags( trim ( nl2br( $comment['content'] ) ), '<br>' ) );

		if ( $comment['content'] != Input::get( 'comment' ) ) {
			$response['errors'][] = 'HTML characters are not allowed and will be removed.';
		}

		// save to database
		if ( $comment['content'] != '' ) {
		    Comment::create( array(
				'location'   => $location,
				'comment'    => $comment['content'],
				'date'       => $comment['date'],
				'conditions' => $comment['conditions']
				)
			);

			$response = array_merge( $response, $comment );

			$response['date'] = $comment['date']->format('F jS, Y');
			$response['success'] = true;
		} else {
			$response['errors'][] = 'Comments cannot be left blank.';
		}



		return Response::json( $response );
	}

}
