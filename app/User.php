<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{

	use Authenticatable, Authorizable;

	protected $fillable = [ 'name', 'email' ];
	protected $hidden   = [ 'password' ];

	const JWT_TOKEN_DURATION_DAYS = 30;

	static function createJWToken( $user_id )
	{
		$jwt_token_key = env( 'JWT_TOKEN_KEY' );

		$header          = [];
		$header[ 'alg' ] = "HMAC-SHA256";
		$header[ 'typ' ] = "JWT";
		$header_json     = json_encode( $header );

		$payload                            = [];
		$payload[ 'http_user_agent' ]       = $_SERVER[ 'HTTP_USER_AGENT' ];
		$payload[ 'http_accept_language' ]  = $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ];
		$payload[ 'remote_addr' ]           = $_SERVER[ 'REMOTE_ADDR' ];
		$payload[ 'token_create_date' ]     = date( "Y-m-d H:i:s" );
		$payload[ 'token_expiration_date' ] = date( "Y-m-d H:i:s", strtotime( "+" . self::JWT_TOKEN_DURATION_DAYS . " days" ) );
		$payload[ 'user_id' ]               = $user_id;
		$payload_json                       = json_encode( $payload );

		$header_and_payload = base64_encode( $header_json ) . "." . base64_encode( $payload_json );
		$signature          = hash_hmac( 'sha256', $header_and_payload, $jwt_token_key );

		return $header_and_payload . "." . base64_encode( $signature );
	}

	static function checkJWToken( $token )
	{
		$jwt_token_key = env( 'JWT_TOKEN_KEY' );
		$token_parts   = explode( '.', $token );

		if ( count( $token_parts ) < 3 )
			return false;

		$header    = $token_parts[ 0 ];
		$payload   = $token_parts[ 1 ];
		$signature = $token_parts[ 2 ];

		// Comprobamos si el token es correcto
		$generated_signature = hash_hmac( 'sha256', $header . $payload, $jwt_token_key );

		if ( $signature !== $generated_signature )
			return false;

		$payload = json_decode( base64_decode( $payload ), true );

		// Check Payload
		if ( strtotime( $payload[ 'token_expiration_date' ] ) < time() || $payload[ 'http_user_agent' ] !== $_SERVER[ 'HTTP_USER_AGENT' ] || $payload[ 'http_accept_language' ] !== $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] || $payload[ 'remote_addr' ] !== $_SERVER[ 'REMOTE_ADDR' ] )
			return false;

		return $payload[ 'user_id' ];
	}
}
