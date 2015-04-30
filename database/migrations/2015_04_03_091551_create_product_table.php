<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('title'); //название продукта
			$table->string('description')->nullable(); //описание продукта
			$table->integer('price'); //цена продукта
			$table->integer('discount')->nullable(); //скидка на продукт
			$table->integer('category_id'); //категория, которой принадлежит продукт
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
