<?php
$orchestraConfig = array(
    'database' => array(
        'driver' => 'pdo_mysql', // only MySQL is supported right now
        'user' => 'root',
        'password' => '',
        'dbname' => 'wp'
    ),
    'csrfSecret' => 'sxZ8vumGNx0x10d7a0GKbN8c0V5DZP', // replace with your secret
    'env' => 'dev', // set this to "prod" when using in production
    'language' => 'en' // only "en" is supported right now
);