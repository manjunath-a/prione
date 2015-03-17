<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTicketTransaction extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			Schema::table('ticket_transaction', function(Blueprint $table)
		{
  	    // $table->integer('total_sku')->unsigned()->after('assigned_to');
  	    // $table->integer('total_images')->unsigned()->after('total_sku');

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
