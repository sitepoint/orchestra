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

namespace Orchestra\Twig;

use Orchestra\Controller;

/**
 * Twig extension for the Orchestra
 * Provides various functions that make template development easier
 */
class PluginBaseExtension extends \Twig_Extension
{
    /**
     * Current request
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * Set the current request
     *
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Define all the added functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'url' => new \Twig_Function_Method($this, 'functionUrl'),
            'path' => new \Twig_Function_Method($this, 'functionPath'),
            'ajax_url' => new \Twig_Function_Method($this, 'functionAjaxUrl'),
            'ajax_path' => new \Twig_Function_Method($this, 'functionAjaxPath'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Orchestra';
    }

    /**
     * Create AJAX URL based on passed $controller and $arguments
     * @see functionUrl
     */
    public function functionAjaxUrl($controllerAction, $arguments = array())
    {
        return $this->functionUrl($controllerAction, $arguments, true);
    }

    /**
     * Create URL based on passed $controller and $arguments
     *
     * @see functionPath
     */
    public function functionUrl($controllerAction, $arguments = array(), $ajax = false)
    {
        return $this->request->getScheme().'://'.$this->request->getHost().$this->functionPath($controllerAction, $arguments, $ajax);
    }

    /**
     * Create AJAX path based on passed $controller and $arguments
     *
     * @see functionPath
     */
    public function functionAjaxPath($controllerAction, $arguments = array())
    {
        return $this->functionPath($controllerAction, $arguments, true);
    }

    /**
     * Create path based on passed $controller and $arguments
     * $controllerAction has to be of form "Controller::action"
     * $arguments can be any array which will be converted into a query string
     *
     * Example: {{ url('Default:index') }}
     *
     * @param $controllerAction
     * @param array $arguments
     * @param boolean $ajax
     * @return string
     */
    public function functionPath($controllerAction, $arguments = array(), $ajax = false)
    {
        $controllerParts = explode(':', $controllerAction, 3);

        if (count($controllerParts) < 2) {
            throw new \InvalidArgumentException('Invalid call of functionPath/functionUrl(). You need to specify controller and action');
        } else {
            $arguments['orchestraAction'] = $controllerParts[1];
            $arguments['orchestraController'] = $controllerParts[0];
            $arguments['page'] = $this->request->query->get('page');
        }

        return Controller::generatePath($this->request, $arguments, $ajax);
    }
}
