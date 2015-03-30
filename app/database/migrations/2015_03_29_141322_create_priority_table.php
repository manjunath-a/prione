<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriorityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('priority', function(Blueprint $table)
		{
            $table->increments('id', true)->unsigned();
            $table->string('priority_name', 255)->unique();
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
            Schema::drop('priority');
	}

}
