<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stage', function(Blueprint $table) {
      $table->increments('id', true)->unsigned();
      $table->string('stage_name', 255)->unique();
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
		Schema::drop('stage');
	}

}
