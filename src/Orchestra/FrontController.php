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