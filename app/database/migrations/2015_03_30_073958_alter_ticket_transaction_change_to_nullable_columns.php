<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketTransactionChangeToNullableColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{


			DB::table('ALTER TABLE `dcst_ticket_transaction`  CHANGE `photographer_id` `photographer_id` INT(10) UNSIGNED NULL DEFAULT NULL,	 CHANGE `mif_id` `mif_id` INT(10) UNSIGNED NULL DEFAULT NULL,	 CHANGE `photosuite_date` `photosuite_date` DATETIME NULL DEFAULT NULL,	 CHANGE `sa_variation` `sa_variation` INT(10) UNSIGNED NULL DEFAULT NULL,	 CHANGE `sa_sku` `sa_sku` INT(10) UNSIGNED NULL DEFAULT NULL,		 CHANGE `notes` `notes` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,		 CHANGE `total_sku` `total_sku` INT(10) UNSIGNED NULL DEFAULT NULL,		 CHANGE `total_images` `total_images` INT(10) UNSIGNED NULL DEFAULT NULL,		 CHANGE `editingteamlead_id` `editingteamlead_id` INT(10) UNSIGNED NULL DEFAULT NULL,			 CHANGE `editor_id` `editor_id` INT(10) UNSIGNED NULL DEFAULT NULL,			 CHANGE `catalogingteamlead_id` `catalogingteamlead_id` INT(10) UNSIGNED NULL DEFAULT NULL,	 CHANGE `cataloguer_id` `cataloguer_id` INT(10) UNSIGNED NULL DEFAULT NULL');
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
