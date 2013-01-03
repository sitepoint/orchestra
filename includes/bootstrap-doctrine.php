<?php
/**
 * Copyright 2012 Michael Sauter <mail@michaelsauter.net>
 * Orchestra is a TripleTime project of SitePoint.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

use Doctrine\Common\Annotations\AnnotationRegistry;

// Check if configuration is present, otherwise include it
if (!isset($orchestraConfig)) {
    include_once __DIR__.'/../config.php';
}

// Create an annotation configuration, using the configured environment
// Tell Doctrine how to autoload the Assert annotations
AnnotationRegistry::registerAutoloadNamespace("Symfony\Component\Validator\Constraint", __DIR__.'/../vendor/symfony/validator');
$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($entityPaths, ($orchestraConfig['env'] == 'dev'), $proxyDir, null, false);
$config->setAutoGenerateProxyClasses(true);

// Table Prefix
$evm = new \Doctrine\Common\EventManager;
global $wpdb;
$tablePrefix = new \Orchestra\DoctrineExtensions\TablePrefix($wpdb->prefix);
$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

// Create an entity manager using the configured DB params and the configuration created above
$em = \Doctrine\ORM\EntityManager::create($orchestraConfig['database'], $config, $evm);

$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');