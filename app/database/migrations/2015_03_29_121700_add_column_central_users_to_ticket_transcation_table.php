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
            $table->integer('editingteamlead_id')->unsigned()->nullable()->default(NULL)->after('total_images');
            $table->integer('editor_id')->unsigned()->nullable()->default(NULL)->after('editingteamlead_id');
            $table->integer('catalogingteamlead_id')->nullable()->default(NULL)->unsigned()->after('editor_id');
            $table->integer('cataloguer_id')->unsigned()->nullable()->default(NULL)->after('catalogingteamlead_id');
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
			$table->dropColumn('editingteamlead_id');
      $table->dropColumn('editor_id');
      $table->dropColumn('catalogingteamlead_id');
      $table->dropColumn('cataloguer_id');
		});
	}

}
