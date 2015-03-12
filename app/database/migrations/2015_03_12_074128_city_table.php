<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		  Schema::create('city', function(Blueprint $table) {

          $table->increments('id', true)->unsigned();
          $table->string('city_name', 255)->unique();
          $table->boolean('status');
          $table->integer('sort');
      });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
			Schema::drop('city');
	}

}


