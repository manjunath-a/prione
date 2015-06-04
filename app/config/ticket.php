<?php

return array(

  'default_priority' => 1,
  'default_status' => 1,
  'default_group' => array(2 => 'Central', 1 => 'Local'),
  'default_stage' => 1,
  // Default Stage ids in DB
  'stage_associateNotAsigned' => 1,
  'stage_associateAsigned' => 2,
  'stage_photoshoot_completed' => 3,
  'stage_mif_completed' => 4,
  'stage_local_qc_completed' => 5,
  'stage_editing_completed' => 6,
  'stage_edited_image_qc_completed' => 7,
  'stage_flat_file_created' => 8,
  'stage_flat_file_qc_compeleted' => 9,
  'stage_asin_created' => 10,

  // Default Role ids Mapped in DB
  // Local team Lead
      'role_ltl' => 8,
  // Photographer
      'role_photogrpaher' => 9,
  // Service Associates
      'role_sa' => 10,
  // Editing Team Lead
      'role_etl' => 6,
  // Editor
      'role_editor' => 7,
  // Cataloging Team Lead
      'role_ctl' => 3,
  // Cataloger
      'role_cat' => 4,

   //  Default Bangalore city lead ID
  'city_lead' => 8, // Bangalore City Lead
  'default_stage_name' => '(Local) Associates Not Assigned',
  'default_group' => 1,
  'photoshoot_location' =>'0:select;Studio:Studio;2:Seller Site',
);