<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidImage;
use App\Exceptions\InvalidParams;
use App\Image;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImagesController extends BaseController
{

	public function __construct()
	{
	}

	public function list()
	{
		return Image::orderBy( 'id', 'desc' )->take( env( 'IMAGES_AT_HOME' ) )->get();
	}

	public function listPage( $page )
	{
		return Image::orderBy( 'id', 'desc' )->skip( $page * env( 'IMAGES_X_PAGE' ) )->take( env( 'IMAGES_X_PAGE' ) )->get();
	}

	public function infoByImageCode( $image_code )
	{
		$img = Image::where( 'image_code', '=', $image_code )->first();

		if ( is_null( $img ) )
			throw new InvalidImage( 'Code: ' . $image_code );

		return $img;

	}

	public function upload( Request $request )
	{
		$this->checkIfBanned( $request );
		$this->checkMaxUploadsInterval( $request );

		$this->uploadCheckIfValid( $request );

		$image_physical_path = $this->uploadSaveInDisk( $request );
		$this->makeThumbnail( $image_physical_path );

		$img = $this->uploadSaveOnDDBB( $request, $image_physical_path );

		return $img;

	}

	private function checkIfBanned( Request $request )
	{
		$banned_ips = [ '213.0.96.101' ];

		if ( in_array( $request->ip(), $banned_ips ) )
			throw new InvalidImage( 'Idiot user detected.' );
	}

	private function checkMaxUploadsInterval( Request $request )
	{
		$user_ip    = $request->ip();
		$from_time  = date( 'Y-m-d H:i:s', strtotime( '-60 minutes' ) );
		$num_images = Image::where( 'ip', '=', $user_ip )->where( 'created_at', '>=', $from_time )->count();

		if ( $num_images > 5 )
			throw new InvalidImage( "You can't upload more photos now." );

	}

	private function uploadCheckIfValid( Request $request )
	{

		if ( $request->hasFile( 'imagen' ) === false || $request->file( 'imagen' )->isValid() === false )
			throw new InvalidParams( $request->imagen->getErrorMessage() );

		$mimetype         = $request->imagen->getMimeType();
		$client_extension = $request->imagen->getClientOriginalExtension();

		switch ( $mimetype ) {
			case 'image/jpeg':
			case 'image/png':
				if ( $client_extension !== 'jpg' && $client_extension !== 'png' )
					throw new InvalidImage( $mimetype . ": " . $client_extension );
				break;
			case 'image/gif':
				if ( $client_extension !== 'gif' )
					throw new InvalidImage( $mimetype . ": " . $client_extension );
				break;
			default:
				throw new InvalidImage( $mimetype . ": " . $client_extension );
				break;
		}

	}

	private function uploadSaveInDisk( Request $request )
	{

		$client_extension = $request->imagen->getClientOriginalExtension();

		$image_name          = time() . rand( 100000, 999999 ) . '.' . $client_extension;
		$image_physical_path = env( 'IMAGES_PATH' ) . $image_name;

		move_uploaded_file( $_FILES[ 'imagen' ][ 'tmp_name' ], $image_physical_path );

		return $image_physical_path;

	}

	private function uploadSaveOnDDBB( Request $request, $image_physical_path )
	{
		$image_ddbb_path = "/" . $image_physical_path;

		$img             = new Image();
		$img->user_id    = NULL;
		$img->name       = '';
		$img->path       = $image_ddbb_path;
		$img->image_code = rand( 100000, 999999 ) . time();
		$img->ip         = $request->ip();
		$img->save();

		return $img;
	}

	private function makeThumbnail( $original_image_path )
	{
		$thumb_path = $original_image_path . env( 'THUMB_SUFFIX' );

		$manager = new ImageManager( array( 'driver' => env( 'IMAGES_DRIVER' ) ) );

		$img = $manager->make( $original_image_path );
		$img->resize( env( 'THUMB_WIDTH' ), env( 'THUMB_HEIGHT' ), function( $constraint ) {
			$constraint->aspectRatio();
		} );
		$img->save( $thumb_path );

	}
}
