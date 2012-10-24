<?php
/**
 * Copyright 2012 Michael Sauter <michael.sauter@sitepoint.com>
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

// Check if configuration is present, otherwise include it
if (!isset($sf2PluginBaseConfig)) {
    include_once __DIR__.'/../config.php';
}

// Create an annotation configuration, using the configured environment
$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, ($sf2PluginBaseConfig['env'] == 'dev'));

// Create an entity manager using the configured DB params and the configuration created above
$em = \Doctrine\ORM\EntityManager::create($sf2PluginBaseConfig['database'], $config);