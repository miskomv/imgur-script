<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{

	public function __construct()
	{
	}

	public function login( Request $request )
	{
		$user = Auth::user();
		var_dump( $user );

	}

	public function register( Request $request )
	{
		$user = Auth::user();
		var_dump( $user );

	}

	public function update( Request $request )
	{
		$user = Auth::user();
		var_dump( $user );

	}
}
