<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketTransactionAddColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ticket_transaction', function(Blueprint $table)
		{
  	    $table->integer('priority')->unsigned()->after('ticket_id');
  	    $table->integer('group_id')->unsigned()->after('priority');
  	    $table->integer('stage_id')->unsigned()->after('group_id');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
