<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

	protected $table    = 'images';
	protected $fillable = [ 'user_id', 'name', 'path', 'image_code', 'ip' ];
	protected $hidden   = [ 'created_at', 'updated_at', 'ip', 'user_id', 'name' ];
	protected $appends  = [ 'facebook_link', 'twitter_link', 'share_link', 'thumbnail' ];

	public function getShareLinkAttribute()
	{
		return 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/' . $this->image_code;
	}

	public function getFacebookLinkAttribute()
	{
		return 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/' . $this->image_code;
	}

	public function getTwitterLinkAttribute()
	{
		return 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/' . $this->image_code;
	}

	public function getThumbnailAttribute()
	{
		return $this->path . env( 'THUMB_SUFFIX' );
	}
}