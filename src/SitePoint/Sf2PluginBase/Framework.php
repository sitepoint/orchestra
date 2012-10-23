<?php
namespace SitePoint\Sf2PluginBase;

use Symfony\Component\Validator\Validation;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\HttpFoundation\Request;

class Framework
{
    private static $frontController;
    private static $csrfSecret;
    private static $language;
    public static $pluginNamespace;
    private static $pluginDirectory;

    public static function init($csrfSecret, $language = 'en')
    {
        self::$csrfSecret = $csrfSecret;
        self::$language = $language;
        include_once __DIR__.'/ClassLoader.php';
        ClassLoader::init();
    }

    public static function setupPlugin($namespace, $baseDirectory)
    {
        self::$pluginNamespace = $namespace;
        self::$pluginDirectory = $baseDirectory;
        self::registerNamespace($namespace, $baseDirectory.'/src/');
    }



    public static function registerController($pluginIdentifier, $directories = array('views' => '/views', 'cache' => '/cache'))
    {
        $request = Request::createFromGlobals();

        if ($request->query->get('page') == $pluginIdentifier) {

            $baseDir = __DIR__.'/../../..';
            $vendorDir = $baseDir.'/vendor';

            // bootstrap.php
            if (!class_exists("Doctrine\Common\Version", false)) {
                include_once(self::$pluginDirectory.'/doctrine-config.php');
                include_once($baseDir.'/bootstrap_doctrine.php');
            }

            // Setup Twig
            $csrfProvider = new DefaultCsrfProvider(self::$csrfSecret);
            $translator = new Translator(self::$language);
            $translator->addLoader('xlf', new XliffFileLoader());
            $translator->addResource('xlf', realpath($vendorDir.'/symfony/form/Symfony/Component/Form/Resources/translations/validators.'.self::$language.'.xlf'), self::$language, 'validators');
            $translator->addResource('xlf', realpath($vendorDir.'/symfony/validator/Symfony/Component/Validator/Resources/translations/validators.'.self::$language.'.xlf'), self::$language, 'validators');
            $loader = new \Twig_Loader_Filesystem(array(
                realpath(self::$pluginDirectory.$directories['views']),
                realpath($vendorDir.'/symfony/twig-bridge/Symfony/Bridge/Twig/Resources/views/Form'),
            ));
            $twigFormEngine = new TwigRendererEngine(array('form_div_layout.html.twig'));
            $twig = new \Twig_Environment($loader, array(
                'cache' => realpath(self::$pluginDirectory.$directories['cache']),
            ));
            $twig->addExtension(new TranslationExtension($translator));
            $twig->addExtension(new FormExtension(new TwigRenderer($twigFormEngine, null)));
            $twigFormEngine->setEnvironment($twig);

            // Set up the form factory with all desired extensions
            $validator = Validation::createValidator();
            $formFactory = Forms::createFormFactoryBuilder()
                ->addExtension(new CsrfExtension($csrfProvider))
                ->addExtension(new ValidatorExtension($validator))
                ->getFormFactory();

            self::$frontController = new FrontController($request, $em, $twig, $formFactory);
        }
    }

    public static function getResponse()
    {
        return self::$frontController->getResponse();
    }

    public static function registerNamespace($namespace, $directory)
    {
        ClassLoader::$classLoader->registerNamespace($namespace, $directory);
    }
}