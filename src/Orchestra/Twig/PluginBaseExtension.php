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
