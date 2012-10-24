<?php
$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, ($sf2_plugin_base_config['env'] == 'dev'));
$em = \Doctrine\ORM\EntityManager::create($sf2_plugin_base_config['database'], $config);