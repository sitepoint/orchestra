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

// Load configuration and UniversalClassLoader
// Introduces $orchestraConfig into the global namespace
include_once __DIR__.'/../config.php';
include_once __DIR__.'/../vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

/**
* Create an instance of UniversalClassLoader and register
* all prefixes / namespaces which are part of Orchestra
*/
$orchestraClassLoader = new UniversalClassLoader();
$orchestraClassLoader->registerPrefixes(array(
    'Twig_'  => __DIR__.'/../vendor/twig/twig/lib',
));
$orchestraClassLoader->registerNamespaces(array(
    'Symfony\\Component\\Yaml' => __DIR__.'/../vendor/symfony/yaml/',
    'Symfony\\Component\\Validator' => __DIR__.'/../vendor/symfony/validator/',
    'Symfony\\Component\\Translation' => __DIR__.'/../vendor/symfony/translation/',
    'Symfony\\Component\\OptionsResolver' => __DIR__.'/../vendor/symfony/options-resolver/',
    'Symfony\\Component\\Locale' => __DIR__.'/../vendor/symfony/locale/',
    'Symfony\\Component\\HttpKernel' => __DIR__.'/../vendor/symfony/http-kernel/',
    'Symfony\\Component\\HttpFoundation' => __DIR__.'/../vendor/symfony/http-foundation/',
    'Symfony\\Component\\Form' => __DIR__.'/../vendor/symfony/form/',
    'Symfony\\Component\\EventDispatcher' => __DIR__.'/../vendor/symfony/event-dispatcher/',
    'Symfony\\Component\\Console' => __DIR__.'/../vendor/symfony/console/',
    'Symfony\\Component\\Config' => __DIR__.'/../vendor/symfony/config/',
    'Symfony\\Component\\ClassLoader' => __DIR__.'/../vendor/symfony/class-loader/',
    'Symfony\\Component\\Filesystem' => __DIR__.'/../vendor/symfony/filesystem/',
    'Symfony\\Bridge\\Twig' => __DIR__.'/../vendor/symfony/twig-bridge/',
    'SessionHandlerInterface' => __DIR__.'/../vendor/symfony/http-foundation/Symfony/Component/HttpFoundation/Resources/stubs',
    'Doctrine\\ORM' => __DIR__.'/../vendor/doctrine/orm/lib/',
    'Doctrine\\DBAL' => __DIR__.'/../vendor/doctrine/dbal/lib/',
    'Doctrine\\Common' => __DIR__.'/../vendor/doctrine/common/lib/',
    'Orchestra' => __DIR__ . '/../src/',
));
$orchestraClassLoader->register();