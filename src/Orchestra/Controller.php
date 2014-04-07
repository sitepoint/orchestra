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

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Acts as a base Controller, providing some helper methods
 */
class Controller
{
    /**
     * Current request
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Entity manager
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Twig templating engine
     *
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * Form factory
     *
     * @var Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    /**
     *
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * Initialize the Controller
     * Sets all attributes and starts the session
     *
     * @param $request
     * @param $em
     * @param $twig
     * @param $formFactory
     */
    public function init($request, $em, $twig, $formFactory)
    {
        $this->request = $request;
        $this->em = $em;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->session = new Session();
        $this->session->start();
    }


    /**
     * Forwards the request to another controller
     * $controller must be either the name of an action,
     * or both controller and action, separated by a double colon
     *
     * Example: "index" or "Default:index"
     *
     * @param string $controller
     * @param array $parameters
     */
    public function forward($controller, $parameters = array())
    {
        if (false === strpos($controller, ':')) {
            $method = $controller.'Action';
            if (method_exists($this, $method)) {
                return call_user_func_array(array($this, $method), $parameters);
            } else {
                throw new \InvalidArgumentException(sprintf('Method "%s" does not exist.', $method));
            }
        } else {
            list($class, $method) = explode(':', $controller, 2);
            $class = Framework::$pluginNamespace.'\\Controller\\'.$class.'Controller';
            $method .= 'Action';
            if (class_exists($class)) {
                $object = new $class();
                if (method_exists($this, $method)) {
                    return call_user_func_array(array($object, $method), $parameters);
                } else {
                    throw new \InvalidArgumentException(sprintf('Method "%s" does not exist.', $method));
                }
            } else {
                throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
            }
        }
    }


    /**
     * Redirects to the given URL
     *
     * @param string $url
     * @param integer $status
     */
    public function redirect($url, $status = 302)
    {
        header('Location: '.$url, true, $status);
    }

    /**
     * Renders a view
     * Adds an additional parameter, "app",
     * which contains the current session
     *
     * @param string $view
     * @param array $parameters
     */
    public function render($view, $parameters = array())
    {
        $view = str_replace(':', '/', $view);
        $parameters['app'] = array(
            'session' => $this->session
        );
        return $this->twig->render($view, $parameters);
    }

    /**
     * Displays error message and ends script
     *
     * @param string $message
     */
    public function createNotFoundException($message = 'Not Found')
    {
        wp_die(__('Page not found.'));
    }

    /**
     * Creates and returns a Form instance from the type of the form
     *
     * @param $type
     * @param null $data
     * @param array $options
     */
    public function createForm($type, $data = null, $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance
     *
     * @param mixed $data
     * @param array $options
     */
    public function createFormBuilder($data = null, $options = array())
    {
        return $this->formFactory->create('form', $data, $options);
    }

    /**
     * Generates an URL based on the current URL.
     *
     * @see Controller::getPathFor()
     * @param array $parameters
     * @return string
     */
    public function getUrlFor($routingData = array(), $parameters = array())
    {

        $port = $this->request->getPort();
        $portStr = ($port != '80') ? ':'.$port : '';

        return $this->request->getScheme().'://'.$this->request->getHost().$portStr.$this->getPathFor($routingData, $parameters);
    }

    /**
     * Generates a path based on the current URL.
     * This is to be used if you want to link to a plugin/controller/action page.
     *
     * Example:
     * $this->getUrlFor(array(
     *     'controller' => 'ControllerName',
     *     'action' => 'actionName'
     * ));
     * $this->getUrlFor('actionName', array('other' => 'param');
     *
     * @see Controller::generatePath()
     * @param array $parameters
     * @return string
     */
    public function getPathFor($routingData = array(), $parameters = array())
    {
        if (is_string($routingData)) {
            $parameters['orchestraAction'] = $routingData;
        } else {
            foreach ($routingData as $key => $value) {
                // Append orchestra to controller and action
                if ($key == 'controller' || $key == 'action') {
                    $key = 'orchestra'.ucfirst($key);
                }
                $parameters[$key] = $value;
            }
        }

        return self::generatePath($this->request, $parameters);
    }

    /**
     * Generates an URL based on the current URL and given $parameters
     *
     * @see Controller::generatePath()
     * @param $request
     * @param array $parameters
     * @param boolean $ajax
     * @return string
     */
    public static function generateUrl($request, $parameters = array(), $ajax = false)
    {
        return $request->getScheme().'://'.$request->getHost().self::generatePath($request, $parameters, $ajax);
    }

    /**
     * Generates a path based on the current URL and given $parameters
     *
     * @param $request
     * @param array $parameters
     * @return string
     */
    public static function generatePath($request, $parameters = array(), $ajax = false)
    {
        if (!isset($parameters['page'])) {
            $parameters['page'] = $request->query->get('page');
        }

        if ($ajax) {
            $adminFile = '/admin-ajax.php';
        } else {
            $adminFile = '/admin.php';
        }

        return $request->getBasePath().$adminFile.'?'.http_build_query($parameters);
    }
}