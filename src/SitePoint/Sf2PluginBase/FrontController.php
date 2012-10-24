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
namespace SitePoint\Sf2PluginBase;

use Symfony\Component\HttpKernel\Controller\ControllerResolver;

/**
 * Handles the routing and gets a response from the called controller
 */
class FrontController
{
    /**
     * Contains the response as returned by the controller
     *
     * @var string
     */
    private $response;

    /**
     * Determines which controller to call based on the current request
     *
     * @param $request
     * @param $em
     * @param $twig
     * @param $formFactory
     */
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

    /**
     * Returns the response
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
}