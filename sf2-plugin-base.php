<?php
/*
Plugin Name: Symfony2 Plugin Base
Description: A solid plugin development base formed of Symfony2 components
Version: 1.0
Author: Michael Sauter
*/
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

// Load configuration and UniversalClassLoader
// Introduces $sf2PluginBaseConfig into the global namespace
include_once __DIR__.'/config.php';
include_once __DIR__.'/vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

/**
 * Create an instance of UniversalClassLoader and register
 * all prefixes / namespaces which are part of Sf2PluginBase
 */
$sf2PluginBaseClassLoader = new UniversalClassLoader();
$sf2PluginBaseClassLoader->registerPrefixes(array(
    'Twig_'  => __DIR__.'/vendor/twig/twig/lib',
));
$sf2PluginBaseClassLoader->registerNamespaces(array(
    'Symfony\\Component\\Yaml' => __DIR__.'/vendor/symfony/yaml/',
    'Symfony\\Component\\Validator' => __DIR__.'/vendor/symfony/validator/',
    'Symfony\\Component\\Translation' => __DIR__.'/vendor/symfony/translation/',
    'Symfony\\Component\\OptionsResolver' => __DIR__.'/vendor/symfony/options-resolver/',
    'Symfony\\Component\\Locale' => __DIR__.'/vendor/symfony/locale/',
    'Symfony\\Component\\HttpKernel' => __DIR__.'/vendor/symfony/http-kernel/',
    'Symfony\\Component\\HttpFoundation' => __DIR__.'/vendor/symfony/http-foundation/',
    'Symfony\\Component\\Form' => __DIR__.'/vendor/symfony/form/',
    'Symfony\\Component\\EventDispatcher' => __DIR__.'/vendor/symfony/event-dispatcher/',
    'Symfony\\Component\\Console' => __DIR__.'/vendor/symfony/console/',
    'Symfony\\Component\\Config' => __DIR__.'/vendor/symfony/config/',
    'Symfony\\Component\\ClassLoader' => __DIR__.'/vendor/symfony/class-loader/',
    'Symfony\\Bridge\\Twig' => __DIR__.'/vendor/symfony/twig-bridge/',
    'SessionHandlerInterface' => __DIR__.'/vendor/symfony/http-foundation/Symfony/Component/HttpFoundation/Resources/stubs',
    'Doctrine\\ORM' => __DIR__.'/vendor/doctrine/orm/lib/',
    'Doctrine\\DBAL' => __DIR__.'/vendor/doctrine/dbal/lib/',
    'Doctrine\\Common' => __DIR__.'/vendor/doctrine/common/lib/',
    'SitePoint' => __DIR__ . '/src/',
));
$sf2PluginBaseClassLoader->register();
