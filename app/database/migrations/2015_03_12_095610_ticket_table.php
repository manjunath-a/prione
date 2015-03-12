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
      $table->string('s3_url', 255);
      $table->integer('assigned_to')->unsigned();
      $table->integer('priority');
      $table->string('pending_reason', 255);
      $table->integer('status_id')->unsigned();
      $table->dateTime('created_at');
      $table->dateTime('updated_at');

      // Foreign Key
      $table->foreign('request_id')->references('id')->on('seller_request')->onDelete('cascade');
      $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
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
