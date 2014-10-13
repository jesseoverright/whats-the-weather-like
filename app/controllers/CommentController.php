<?php

class CommentController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json( Comment::get() );
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Comment::create( array(
			'location'   => Input::get( 'location' ),
			'comment'    => Input::get( 'comment' ),
			'date'       => Input::get( 'date' ),
			'conditions' => Input::get( 'conditions' )
			)
		);

		return Response::json( array(
			'success' => true
			)
		);
	}

}
