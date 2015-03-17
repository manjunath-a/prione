<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGroup extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group', function(Blueprint $table) {
      $table->increments('id', true)->unsigned();
      $table->string('group_name', 255)->unique();
      $table->boolean('status');
      $table->integer('sort');
    });

    Schema::table('ticket_transaction', function(Blueprint $table) {
			$table->foreign('group_id')->references('id')->on('group')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('group');
	}

}
