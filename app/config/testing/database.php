<?php

return array(

    'default' => 'sqlite',

    'connections' => array(
       'mysql' => array(
          'driver'    => 'mysql',
          'host'      => 'localhost',
          'database'  => 'prione',
          'username'  => 'root',
          'password'  => 'secret',
          'charset'   => 'utf8',
          'collation' => 'utf8_unicode_ci',
          'prefix'    => 'dcst_',
        ),

    )
);