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
namespace Sf2PluginBase;

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
use Sf2PluginBase\Twig\WordpressProxy;
use Sf2PluginBase\Twig\PluginBaseExtension;

/**
 * Central class of Sf2PluginBase
 *
 * Plugins can use this class to tap into the functionality of Sf2PluginBase.
 * To do this, they need to call Framework::setupPlugin() during the "admin_menu" hook.
 * To get the returned content of Sf2PluginBase, they need to call Framework::getResponse().
 */
class Framework
{
    /**
     * Front controller
     *
     * @var Sf2PluginBase\FrontController
     */
    private static $frontController;

    /**
     * Namespace of the active plugin
     *
     * @var string
     */
    public static $pluginNamespace;

    /**
     * Current request
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    private static $request;

    /**
     * Determines if the calling plugin is currently active (that is, it was requested).
     * If so, configure Sf2PluginBase for this plugin (e.g. setting namespace, directories etc.)
     * and construct a front controller to handle the current request.
     *
     * @param $pluginNamespace
     * @param $pluginDirectory
     * @param array $additionalNamespaces
     * @param array $additionalPrefixes
     * @param array $directories
     * @return mixed
     */
    public static function setupPlugin($pluginNamespace, $pluginDirectory, $additionalNamespaces = array(), $additionalPrefixes = array(), $directories = array('src' => '/src', 'views' => '/views', 'cache' => '/cache'))
    {
        global $sf2PluginBaseConfig;
        global $sf2PluginBaseClassLoader;

        // If there is no $request set yet, create one from globals
        // This needs to be done only once because the request is the
        // same, no matter which plugin called Framework::setupPlugin()
        if (!self::$request) {
            self::$request = Request::createFromGlobals();
        }

        // Generate plugin identifier based on the namespace by stripping the backslashes
        $pluginIdentifier = str_replace('\\', '', $pluginNamespace);

        // Only proceed if the requested page is the calling plugin
        if (self::$request->query->get('page') == $pluginIdentifier) {

            // Register the namespace of the calling plugin
            // and any additional namespaces passed
            $additionalNamespaces[$pluginNamespace] = $pluginDirectory.$directories['src'].'/';
            $sf2PluginBaseClassLoader->registerNamespaces($additionalNamespaces);

            // Register prefixes is passed
            if (count($additionalPrefixes) > 0) {
                $sf2PluginBaseClassLoader->registerPrefixes($additionalPrefixes);
            }

            self::$pluginNamespace = $pluginNamespace;
            $baseDir = __DIR__.'/../..';
            $vendorDir = $baseDir.'/vendor';

            // Boot Doctrine and use the configuration of the active plugin
            if (!class_exists("Doctrine\Common\Version", false)) {
                include_once($pluginDirectory.'/doctrine-config.php');
                include_once($baseDir.'/includes/bootstrap-doctrine.php');
            }

            // Setup Twig
            $translator = new Translator($sf2PluginBaseConfig['language']);
            $translator->addLoader('xlf', new XliffFileLoader());
            $translator->addResource('xlf', realpath($vendorDir.'/symfony/form/Symfony/Component/Form/Resources/translations/validators.'.$sf2PluginBaseConfig['language'].'.xlf'), $sf2PluginBaseConfig['language'], 'validators');
            $translator->addResource('xlf', realpath($vendorDir.'/symfony/validator/Symfony/Component/Validator/Resources/translations/validators.'.$sf2PluginBaseConfig['language'].'.xlf'), $sf2PluginBaseConfig['language'], 'validators');
            $loader = new \Twig_Loader_Filesystem(array(
                realpath($pluginDirectory.$directories['views']),
                realpath($vendorDir.'/symfony/twig-bridge/Symfony/Bridge/Twig/Resources/views/Form'),
            ));
            $twigFormEngine = new TwigRendererEngine(array('form_div_layout.html.twig'));
            $twigEnvironmentOptions = array();
            if ($sf2PluginBaseConfig['mode'] == 'prod') {
                $twigEnvironmentOptions['cache'] = realpath($pluginDirectory.$directories['cache']);
            } else {
                $twigEnvironmentOptions['cache'] = false;
            }
            $twig = new \Twig_Environment($loader, $twigEnvironmentOptions);
            $twig->addGlobal('wp', new WordpressProxy());
            $twig->addExtension(new PluginBaseExtension(self::$request));
            $twig->addExtension(new TranslationExtension($translator));
            $twig->addExtension(new FormExtension(new TwigRenderer($twigFormEngine, null)));
            $twigFormEngine->setEnvironment($twig);

            // Setup the form factory with all CSRF and validator extensions
            $csrfProvider = new DefaultCsrfProvider($sf2PluginBaseConfig['csrfSecret']);
            $validator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator();
            $formFactory = Forms::createFormFactoryBuilder()
                ->addExtension(new CsrfExtension($csrfProvider))
                ->addExtension(new ValidatorExtension($validator))
                ->getFormFactory();

            // Instantiate FrontController
            self::$frontController = new FrontController(self::$request, $em, $twig, $formFactory);
        }

        // Return the generated plugin identifier for use
        // inside the plugin, e.g. in add_menu_page()
        return $pluginIdentifier;
    }

    /**
     * Returns the response stored in the front controller
     *
     * @return mixed
     */
    public static function getResponse()
    {
        return self::$frontController->getResponse();
    }
}