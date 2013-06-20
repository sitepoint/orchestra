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
include_once $orchestraConfig['vendorDir'].'/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

/**
* Create an instance of UniversalClassLoader and register
* all prefixes / namespaces which are part of Orchestra
*/
$orchestraClassLoader = new UniversalClassLoader();
$orchestraClassLoader->registerPrefixes(array(
    'Twig_'  => $orchestraConfig['vendorDir'].'/twig/twig/lib',
));
$orchestraClassLoader->registerNamespaces(array(
    'Symfony\\Component\\Yaml' => $orchestraConfig['vendorDir'].'/symfony/yaml/',
    'Symfony\\Component\\Validator' => $orchestraConfig['vendorDir'].'/symfony/validator/',
    'Symfony\\Component\\Translation' => $orchestraConfig['vendorDir'].'/symfony/translation/',
    'Symfony\\Component\\OptionsResolver' => $orchestraConfig['vendorDir'].'/symfony/options-resolver/',
    'Symfony\\Component\\Locale' => $orchestraConfig['vendorDir'].'/symfony/locale/',
    'Symfony\\Component\\HttpKernel' => $orchestraConfig['vendorDir'].'/symfony/http-kernel/',
    'Symfony\\Component\\HttpFoundation' => $orchestraConfig['vendorDir'].'/symfony/http-foundation/',
    'Symfony\\Component\\Form' => $orchestraConfig['vendorDir'].'/symfony/form/',
    'Symfony\\Component\\EventDispatcher' => $orchestraConfig['vendorDir'].'/symfony/event-dispatcher/',
    'Symfony\\Component\\Console' => $orchestraConfig['vendorDir'].'/symfony/console/',
    'Symfony\\Component\\Config' => $orchestraConfig['vendorDir'].'/symfony/config/',
    'Symfony\\Component\\ClassLoader' => $orchestraConfig['vendorDir'].'/symfony/class-loader/',
    'Symfony\\Component\\Filesystem' => $orchestraConfig['vendorDir'].'/symfony/filesystem/',
    'Symfony\\Component\\PropertyAccess' => $orchestraConfig['vendorDir'].'/symfony/property-access/',
    'Symfony\\Bridge\\Twig' => $orchestraConfig['vendorDir'].'/symfony/twig-bridge/',
    'SessionHandlerInterface' => $orchestraConfig['vendorDir'].'/symfony/http-foundation/Symfony/Component/HttpFoundation/Resources/stubs',
    'Doctrine\\ORM' => $orchestraConfig['vendorDir'].'/doctrine/orm/lib/',
    'Doctrine\\DBAL' => $orchestraConfig['vendorDir'].'/doctrine/dbal/lib/',
    'Doctrine\\Common' => $orchestraConfig['vendorDir'].'/doctrine/common/lib/',
    'Orchestra' => __DIR__ . '/../src/',
));
$orchestraClassLoader->register();