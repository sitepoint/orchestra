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
namespace Orchestra;

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
     * Sets the controller based on "page" (Wordpress' way of figuring out which plugin is called),
     * "orchestraController" and "orchestraAction"
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
            Framework::$pluginNamespace.'\\Controller\\'.ucfirst($request->query->get('orchestraController', 'default')).'Controller::'.$request->query->get('orchestraAction', 'index').'Action';
        unset($attributes['orchestraController']);
        unset($attributes['orchestraAction']);
        unset($attributes['page']);

        $resolver = new ControllerResolver();
        $request->attributes->add($attributes);
        $controller = $resolver->getController($request);
        $controller[0]->init($request, $em, $twig, $formFactory);
        $arguments = $resolver->getArguments($request, $controller);
        $this->response = call_user_func_array($controller, $arguments);
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