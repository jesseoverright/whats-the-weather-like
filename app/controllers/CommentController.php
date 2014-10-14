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

		return Response::json( $comments );
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Comment::create( array(
			'location'   => Input::post( 'location' ),
			'comment'    => Input::post( 'comment' ),
			'date'       => Input::post( 'date' ),
			'conditions' => Input::post( 'conditions' )
			)
		);

		return Response::json( array(
			'success' => true
			)
		);
	}

}
