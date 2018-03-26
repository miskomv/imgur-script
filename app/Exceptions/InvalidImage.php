<?php

namespace App\Exceptions;

use Exception;

class InvalidImage extends Exception
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
		return response()->json( [ 'Error' => 'Invalid image type', 'Details' => $this->message ], 400 );
	}
}