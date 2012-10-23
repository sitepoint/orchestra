<?php
namespace SitePoint\Sf2PluginBase;

require_once(__DIR__.'/../../../vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php');

use Symfony\Component\ClassLoader\UniversalClassLoader;

class ClassLoader
{
    public static $classLoader;

    public static function init()
    {
        $baseDir = __DIR__.'/../../..';
        $vendorDir = $baseDir.'/vendor';

        self::$classLoader = new UniversalClassLoader();
        self::$classLoader->registerPrefixes(array(
            'Twig_'  => $vendorDir.'/twig/twig/lib',
        ));
        self::$classLoader->registerNamespaces(array(
            'Symfony\\Component\\Yaml' => $vendorDir . '/symfony/yaml/',
            'Symfony\\Component\\Validator' => $vendorDir . '/symfony/validator/',
            'Symfony\\Component\\Translation' => $vendorDir . '/symfony/translation/',
            'Symfony\\Component\\OptionsResolver' => $vendorDir . '/symfony/options-resolver/',
            'Symfony\\Component\\Locale' => $vendorDir . '/symfony/locale/',
            'Symfony\\Component\\HttpKernel' => $vendorDir . '/symfony/http-kernel/',
            'Symfony\\Component\\HttpFoundation' => $vendorDir . '/symfony/http-foundation/',
            'Symfony\\Component\\Form' => $vendorDir . '/symfony/form/',
            'Symfony\\Component\\EventDispatcher' => $vendorDir . '/symfony/event-dispatcher/',
            'Symfony\\Component\\Console' => $vendorDir . '/symfony/console/',
            'Symfony\\Component\\Config' => $vendorDir . '/symfony/config/',
            'Symfony\\Component\\ClassLoader' => $vendorDir . '/symfony/class-loader/',
            'Symfony\\Bridge\\Twig' => $vendorDir . '/symfony/twig-bridge/',
            'SitePoint' => $baseDir . '/src/',
            'SessionHandlerInterface' => $vendorDir . '/symfony/http-foundation/Symfony/Component/HttpFoundation/Resources/stubs',
            'Doctrine\\ORM' => $vendorDir . '/doctrine/orm/lib/',
            'Doctrine\\DBAL' => $vendorDir . '/doctrine/dbal/lib/',
            'Doctrine\\Common' => $vendorDir . '/doctrine/common/lib/',
        ));
        self::$classLoader->register();
    }
}