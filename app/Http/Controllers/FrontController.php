<?php

namespace App\Http\Controllers;

use App\Image;
use Laravel\Lumen\Routing\Controller as BaseController;

class FrontController extends BaseController
{

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
		return view( 'front.app', $this->page_params );
	}
}