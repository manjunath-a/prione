<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket_transaction', function(Blueprint $table) {
				$table->increments('id', true)->unsigned();
				$table->integer('ticket_id')->unsigned();;
				$table->string('comment', 255);
				$table->integer('status_id')->unsigned();;
				$table->integer('assigned_to')->unsigned();;
				$table->dateTime('created_at');
      	$table->dateTime('updated_at');
      	// Foreign Key
	      $table->foreign('ticket_id')->references('id')->on('ticket')->onDelete('cascade');
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
		Schema::drop('ticket_transaction');
	}

}
