<?php
$orchestraConfig = array(
    'database' => array(
        'driver' => 'pdo_mysql', // only MySQL is supported right now
        'host' => (defined('DB_HOST') ? DB_HOST : 'localhost'),
        'user' => (defined('DB_USER') ? DB_USER : 'root'),
        'password' => (defined('DB_PASSWORD') ? DB_PASSWORD : ''),
        'dbname' => (defined('DB_NAME') ? DB_NAME : 'wordpress')
    ),
    'csrfSecret' => (defined('AUTH_SALT') ? AUTH_SALT : 'auth_salt'),
    'env' => ((defined('WP_DEBUG') ? WP_DEBUG : false) ? 'dev' : 'prod'),
    'language' => 'en' // only "en" is supported right now
);