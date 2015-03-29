<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCentralUsersToTicketTranscationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ticket_transaction', function(Blueprint $table)
		{
            //By default assign to admin
            $table->integer('editingteamlead_id')->unsigned()->default(1)->after('total_images');
            $table->integer('editor_id')->unsigned()->default(1)->after('editingteamlead_id');
            $table->integer('catalogingteamlead_id')->default(1)->unsigned()->after('editor_id');
            $table->integer('cataloguer_id')->unsigned()->default(1)->after('catalogingteamlead_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ticket_transaction', function(Blueprint $table)
		{
			//
		});
	}

}
