<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group( [ 'prefix' => 'images' ], function() use ( $router ) {

	$router->get( 'list', 'ImagesController@list' );
	$router->get( 'list/{page:\d+}', 'ImagesController@listPage' );
	$router->get( 'info/{image_id:\d{16}}', 'ImagesController@infoByImageCode' );
	$router->post( 'upload', 'ImagesController@upload' );
} );

$router->group( [ 'prefix' => 'users' ], function() use ( $router ) {

	$router->post( 'login', 'UserController@login' );
	$router->post( 'register', 'UserController@login' );

	$router->group( [ 'middleware' => 'auth' ], function() use ( $router ) {
		$router->post( 'update', [ 'middleware' => 'auth', 'uses' => 'UserController@update' ] );
	} );

} );

$router->get( '/', 'FrontController@home' );
$router->get( '/{image_code:\d+}', 'FrontController@home' );