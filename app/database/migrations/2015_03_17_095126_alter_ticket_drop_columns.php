<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketDropColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ticket', function(Blueprint $table)
		{

  	    $table->dropForeign('ticket_assigned_to_foreign');
  	    $table->dropForeign('users_assigned_to_foreign');
  	    $table->dropForeign('ticket_assigned_to_foreign');
  	    $table->dropForeign('ticket_status_id_foreign');
  	    $table->dropColumn('priority');
  	    $table->dropColumn('assigned_to');
  	    $table->dropColumn('pending_reason');
  	    $table->dropColumn('status_id');
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
