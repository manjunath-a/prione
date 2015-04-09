<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('ticket', function(Blueprint $table) {
		 	$table->increments('id', true)->unsigned();
      $table->integer('request_id')->unsigned();
      $table->string('email', 150);
      $table->string('subject', 255);
      $table->text('description', 255);
      $table->string('s3_folder', 255);
      $table->dateTime('created_at');
      $table->dateTime('updated_at');

    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ticket');
	}

}
