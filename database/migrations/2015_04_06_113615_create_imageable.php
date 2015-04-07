<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('imageables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('image_id');
			$table->integer('imageable_id');
			$table->string('imageable_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('imageables');
	}

}
