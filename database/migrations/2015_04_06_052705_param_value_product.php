<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParamValueProduct extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('param_value_product', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('product_id');
			$table->integer('param_value_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('param_value_product');
	}

}
