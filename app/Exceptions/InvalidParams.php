<?php

namespace App\Exceptions;

use Exception;

class InvalidParams extends Exception
{

	/**
	 * Report the exception.
	 *
	 * @return void
	 */
	public function report( Exception $e )
	{
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function render( $request )
	{
		return response()->json( [ 'Error' => 'Invalid params for request', 'Details' => $this->message ], 400 );
	}
}