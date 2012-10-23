<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$dbParams = array(
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'password' => '',
    'dbname' => 'wp'
);
$isDevMode = false;
$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$em = EntityManager::create($dbParams, $config);