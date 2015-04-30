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
			$table->integer('image_id'); //id изображения
			$table->integer('imageable_id'); //id сущности к которой относится изображение
			$table->string('imageable_type'); //тип сущности к которой относится изображение (App\Product или App\Post)
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
