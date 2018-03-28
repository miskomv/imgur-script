<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

	protected $table    = 'images';
	protected $fillable = [ 'user_id', 'name', 'path', 'image_code', 'ip' ];
	protected $hidden   = [ 'created_at', 'updated_at', 'ip', 'user_id', 'name' ];
	protected $appends  = [ 'facebook_link', 'twitter_link', 'thumbnail' ];

	public function getFacebookLinkAttribute()
	{
		$facebook_share = 'https://www.facebook.com/sharer/sharer.php?u=';
		$image_link     = 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/' . $this->image_code;
		return $facebook_share . $image_link;
	}

	public function getTwitterLinkAttribute()
	{
		$twitter_share = 'http://twitter.com/share?';
		$twitter_text  = 'text=Awesome Image';
		$image_link    = '&url=http://' . $_SERVER[ 'HTTP_HOST' ] . '/' . $this->image_code;
		$twitter_tags  = '&hashtags=ImgurMV,mediavida';
		return $twitter_share . $twitter_text . $image_link . $twitter_tags;
	}

	public function getThumbnailAttribute()
	{
		return $this->path . env( 'THUMB_SUFFIX' );
	}
}