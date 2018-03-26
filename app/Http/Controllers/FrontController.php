<?php

namespace App\Http\Controllers;

use App\Image;
use Laravel\Lumen\Routing\Controller as BaseController;

class FrontController extends BaseController
{

	CONST HTML_PATH = 'front/';

	private $page_domain      = '';
	private $page_url         = '';
	private $page_title       = 'Imgur MV Script';
	private $page_description = 'Imgur MV Script';
	private $page_keywords    = 'Imgur MV Script';
	private $page_image       = '/front/assets/images/logo.jpg';
	private $page_params      = [];

	public function __construct()
	{
		$this->page_domain = $_SERVER[ 'HTTP_HOST' ];
		$this->page_url    = $_SERVER[ 'REQUEST_URI' ];

		$this->page_params[ 'page_domain' ]      = $this->page_domain;
		$this->page_params[ 'page_url' ]         = $this->page_url;
		$this->page_params[ 'page_title' ]       = $this->page_title;
		$this->page_params[ 'page_description' ] = $this->page_description;
		$this->page_params[ 'page_keywords' ]    = $this->page_keywords;
		$this->page_params[ 'page_image' ]       = $this->page_image;
	}

	public function home()
	{
		return response( $this->loadTemplate( 'app.html' ) );
	}

	public function imageDetails( $image_code )
	{
		$image = Image::where( 'image_code', '=', $image_code )->first();

		$this->page_params[ 'page_title' ]       .= ' - Image ' . $image->image_code;
		$this->page_params[ 'page_description' ] .= ' - Image ' . $image->image_code;
		$this->page_params[ 'page_image' ]       = $image->path;

		return response( $this->loadTemplate( 'app.html' ) );
	}

	private function loadTemplate( $file )
	{
		$mustache = new \Mustache_Engine();
		return $mustache->render( file_get_contents( self::HTML_PATH . $file ), $this->page_params );
	}
}