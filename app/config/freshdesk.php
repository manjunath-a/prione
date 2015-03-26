<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Fresh Desk API Information
	|--------------------------------------------------------------------------

	*/
	// Domain URL
	'url' => 'https://compassnet.freshdesk.com',
	// Access tocken
	'token' => 'k0FfSA2BHE9Yxw22ww',
	// Password
	'password' => 'X',
	 // Custome Fields at FreshDesk
  'custom_fields' => array(
  	'stage_203188' =>'stage_id',
  	'city_203188' =>'city_id' ,
  	'no_of_skus_to_be_cataloged_203188' =>'total_sku',
  	'no_of_skus_imaged_203188' => 'no_of_sku_images',
  	'no_of_images_203188' =>'total_images',
  	's3_bucket_images_amp_mif_203188' => 's3_url' ,
  	'helpdesk_ticket_group_id' => 'group_id',
  	),
);
