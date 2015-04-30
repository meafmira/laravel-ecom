<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParamValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('param_values', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('param_id'); //id параметра которому принадлежит значение
			$table->string('value'); //значение параметра
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('param_values');
	}

}
