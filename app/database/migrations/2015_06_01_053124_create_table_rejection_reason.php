<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRejectionReason extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			Schema::create('rejection_reason', function(Blueprint $table) {
	      $table->increments('id', true)->unsigned();
	      $table->string('rejection_reason', 255)->unique();
	      $table->boolean('status');
	      $table->integer('sort');
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
