<?php
$orchestraConfig = array(
    'database' => array(
        'driver' => 'pdo_mysql', // only MySQL is supported right now
        'host' => DB_HOST,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'dbname' => DB_NAME
    ),
    'csrfSecret' => AUTH_SALT,
    'env' => (WP_DEBUG ? 'dev' : 'prod'),
    'language' => 'en' // only "en" is supported right now
);