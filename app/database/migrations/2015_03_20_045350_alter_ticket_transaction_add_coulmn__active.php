<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketTransactionAddCoulmnActive extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ticket_transaction', function(Blueprint $table) 	{
			$table->tinyInteger('active')->unsigned()->after('stage_id');
			$table->integer('photographer_id')->unsigned()->nullable()->after('active');
			$table->integer('mif_id')->unsigned()->nullable()->after('photographer_id');
			$table->dateTime('photoshoot_date')->nullable()->after('mif_id');
			$table->string('photoshoot_location')->nullable()->after('photoshoot_date');
			$table->integer('sa_variation')->unsigned()->nullable()->after('photoshoot_location');
			$table->integer('sa_sku')->unsigned()->nullable()->after('sa_variation');
			$table->text('notes')->nullable()->after('sa_sku');

			$table->integer('editingteamlead_id')->unsigned()->nullable()->default(NULL)->after('total_images');
      $table->integer('editor_id')->unsigned()->nullable()->default(NULL)->after('editingteamlead_id');
      $table->integer('catalogingteamlead_id')->nullable()->default(NULL)->unsigned()->after('editor_id');
      $table->integer('cataloguer_id')->unsigned()->nullable()->default(NULL)->after('catalogingteamlead_id');
      $table->integer('editingmanager_id')->unsigned()->nullable()->default(NULL)->after('total_images');
      $table->integer('localteamlead_id')->unsigned()->nullable()->default(NULL)->after('active');
      $table->integer('catalogingmanager_id')->nullable()->default(NULL)->unsigned()->after('editor_id');
      $table->integer('created_by')->unsigned()->nullable()->after('cataloguer_id');

      $table->foreign('photographer_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('mif_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('editingmanager_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('localteamlead_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('catalogingmanager_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('editingteamlead_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('editor_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('catalogingteamlead_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('cataloguer_id')->references('id')->on('users')->onDelete('restrict');
      $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
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
