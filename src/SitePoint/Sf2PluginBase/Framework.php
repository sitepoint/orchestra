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
    public static $pluginNamespace;
    private static $request;

    public static function setupPlugin($pluginNamespace, $pluginDirectory, $additionalNamespaces = array(), $additionalPrefixes = array(), $directories = array('views' => '/views', 'cache' => '/cache'))
    {
        global $sf2_plugin_base_config;

        if (!self::$request) {
            self::$request = Request::createFromGlobals();
        }

        $pluginIdentifier = str_replace('\\', ':', $pluginNamespace);

        if (self::$request->query->get('page') == $pluginIdentifier) {

            $additionalNamespaces[$pluginNamespace] = $pluginDirectory.'/src/';
            ClassLoader::$classLoader->registerNamespaces($additionalNamespaces);

            if (count($additionalPrefixes) > 0) {
                ClassLoader::$classLoader->registerPrefixes($additionalPrefixes);
            }

            self::$pluginNamespace = $pluginNamespace;

            $baseDir = __DIR__.'/../../..';
            $vendorDir = $baseDir.'/vendor';

            // bootstrap.php
            if (!class_exists("Doctrine\Common\Version", false)) {
                include_once($pluginDirectory.'/doctrine-config.php');
                include_once($baseDir.'/includes/bootstrap-doctrine.php');
            }

            // Setup Twig
            $csrfProvider = new DefaultCsrfProvider($sf2_plugin_base_config['csrfSecret']);
            $translator = new Translator($sf2_plugin_base_config['language']);
            $translator->addLoader('xlf', new XliffFileLoader());
            $translator->addResource('xlf', realpath($vendorDir.'/symfony/form/Symfony/Component/Form/Resources/translations/validators.'.$sf2_plugin_base_config['language'].'.xlf'), $sf2_plugin_base_config['language'], 'validators');
            $translator->addResource('xlf', realpath($vendorDir.'/symfony/validator/Symfony/Component/Validator/Resources/translations/validators.'.$sf2_plugin_base_config['language'].'.xlf'), $sf2_plugin_base_config['language'], 'validators');
            $loader = new \Twig_Loader_Filesystem(array(
                realpath($pluginDirectory.$directories['views']),
                realpath($vendorDir.'/symfony/twig-bridge/Symfony/Bridge/Twig/Resources/views/Form'),
            ));
            $twigFormEngine = new TwigRendererEngine(array('form_div_layout.html.twig'));
            $twigEnvironmentOptions = array();
            if ($sf2_plugin_base_config['mode'] == 'prod') {
                $twigEnvironmentOptions['cache'] = realpath($pluginDirectory.$directories['cache']);
            } else {
                $twigEnvironmentOptions['cache'] = false;
            }
            $twig = new \Twig_Environment($loader, $twigEnvironmentOptions);
            $twig->addExtension(new TranslationExtension($translator));
            $twig->addExtension(new FormExtension(new TwigRenderer($twigFormEngine, null)));
            $twigFormEngine->setEnvironment($twig);

            // Set up the form factory with all desired extensions
            $validator = Validation::createValidator();
            $formFactory = Forms::createFormFactoryBuilder()
                ->addExtension(new CsrfExtension($csrfProvider))
                ->addExtension(new ValidatorExtension($validator))
                ->getFormFactory();

            self::$frontController = new FrontController(self::$request, $em, $twig, $formFactory);
        }

        return $pluginIdentifier;
    }

    public static function getResponse()
    {
        return self::$frontController->getResponse();
    }
}