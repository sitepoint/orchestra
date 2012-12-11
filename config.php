<?php
$orchestraConfig = array(
    'database' => array(
        'driver' => 'pdo_mysql', // only MySQL is supported right now
        'host' => DB_HOST,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'dbname' => DB_NAME
    ),
    'csrfSecret' => 'sxZ8vumGNx0x10d7a0GKbN8c0V5DZP', // replace with your secret
    'env' => 'dev', // set this to "prod" when using in production
    'language' => 'en' // only "en" is supported right now
);