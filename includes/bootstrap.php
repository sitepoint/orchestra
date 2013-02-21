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
    'Doctrine\\DBAL\\Migrations' => __DIR__.'/../vendor/doctrine/migrations/lib/',
    'Doctrine\\Common' => __DIR__.'/../vendor/doctrine/common/lib/',
    'Orchestra' => __DIR__ . '/../src/',
));
$orchestraClassLoader->register();