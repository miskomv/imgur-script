<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Initial extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create( 'users', function( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'email' );
			$table->string( 'password' );
			$table->timestamps();

			$table->unique( 'email' );
		} );

		Schema::create( 'images', function( Blueprint $table ) {
			$table->increments( 'id' );
			$table->integer( 'user_id', false, true )->nullable();
			$table->string( 'name' );
			$table->string( 'path' );
			$table->string( 'image_code' );
			$table->string( 'ip' );
			$table->timestamps();

			$table->unique( 'path' );
			$table->index( [ 'image_code' ] );

			$table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete('cascade')->onUpdate('cascade');
		} );

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop( 'images' );
		Schema::drop( 'users' );
	}
}
