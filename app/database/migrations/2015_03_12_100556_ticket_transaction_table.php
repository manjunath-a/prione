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
				$table->integer('status_id')->unsigned();;
				$table->integer('assigned_to')->unsigned();;
				$table->integer('total_sku')->unsigned()->nullable();
  	    $table->integer('total_images')->unsigned()->nullable();
				$table->dateTime('created_at');
      	$table->dateTime('updated_at');
      	// Foreign Key
	      $table->foreign('ticket_id')->references('id')->on('ticket')->onDelete('restrict');
	      $table->foreign('assigned_to')->references('id')->on('users')->onDelete('restrict');
	      $table->foreign('status_id')->references('id')->on('status')->onDelete('restrict');
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
