<?php
namespace SitePoint\Sf2PluginBase;

use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class FrontController
{
    private $response;

    public function __construct($request, $em, $twig, $formFactory)
    {
        $attributes = $request->query->all();
        $attributes['_controller'] =
            Framework::$pluginNamespace.'\\Controller\\'.ucfirst($request->query->get('controller', 'default')).'Controller::'.$request->query->get('action', 'index').'Action';
        unset($attributes['controller']);
        unset($attributes['action']);
        unset($attributes['page']);

        $resolver = new ControllerResolver();

        try {
            $request->attributes->add($attributes);
            $controller = $resolver->getController($request);
            $controller[0]->init($request, $em, $twig, $formFactory);
            $arguments = $resolver->getArguments($request, $controller);
            $this->response = call_user_func_array($controller, $arguments);
        } catch (Exception $e) {
            $this->response = 'An error occurred.';
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}