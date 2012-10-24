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
namespace SitePoint\Sf2PluginBase\Twig;

use SitePoint\Sf2PluginBase\Controller;

/**
 * Twig extension for the Sf2PluginBase
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
            'pluginUrl' => new \Twig_Function_Method($this, 'functionPluginUrl'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Sf2PluginBase';
    }

    /**
     * Create URL based on passed $controller and $arguments
     * $controller has to be either of form "Plugin::Controller::action" or "Controller::action"
     * $arguments can be any array which will be converted into a query string
     *
     * Example: {{ pluginUrl('MyPlugin::Default::index') }}
     *
     * @param $controller
     * @param array $arguments
     * @return string
     */
    public function functionPluginUrl($controller, $arguments = array())
    {
        list($arguments['page'], $arguments['controller'], $arguments['action']) = explode('::', $controller, 3);

        if (!$arguments['page'] || !$arguments['controller']) {
            throw new \InvalidArgumentException('Invalid call of pluginUrl(). You need to specify at least controller and action');
        }

        if (!$arguments['action']) {
            $arguments['action'] = $arguments['controller'];
            $arguments['controller'] = $arguments['page'];
            $arguments['page'] = $this->request->query->get('page');
        }

        return Controller::generateUrl($this->request, $arguments);
    }
}
