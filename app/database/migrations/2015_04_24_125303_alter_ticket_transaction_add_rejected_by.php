<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketTransactionAddRejectedBy extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ticket_transaction', function(Blueprint $table)
		{
				$table->integer('rejected_role')->unsigned()->nullable()->default(NULL)->after('pending_reason_id');
				$table->boolean('read_status')->default(true)->nullable()->after('status_id');
				$table->foreign('rejected_role')->references('id')->on('roles')->onDelete('cascade');
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
