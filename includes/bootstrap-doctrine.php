<?php
/**
 * Copyright (c) 2012-2013 Michael Sauter <mail@michaelsauter.net>
 * Orchestra originated from a TripleTime project of SitePoint.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
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