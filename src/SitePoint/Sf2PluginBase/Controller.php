<?php
namespace SitePoint\Sf2PluginBase;


class Controller
{
    protected $request;
    protected $em;
    protected $twig;
    protected $formFactory;
    protected $session;

    public function init($request, $em, $twig, $formFactory)
    {
        $this->request = $request;
        $this->em = $em;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->session = new \Symfony\Component\HttpFoundation\Session\Session();
        $this->session->start();
    }


    /**
     * Forwards the request to another controller.
     *
     * @param string $callable
     * @param array $parameters
     */
    public function forward($controller, $parameters = array())
    {
        if (false === strpos($controller, '::')) {
            $method = $controller.'Action';
            if (method_exists($this, $method)) {
                return call_user_func_array(array($this, $method), $parameters);
            }
        } else {
            list($class, $method) = explode('::', $controller, 2);
            $class = Framework::$pluginNamespace.'\\Controller\\'.$class.'Controller';
            $method .= 'Action';
            if (!class_exists($class)) {
                throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
            }
            $object = new $class();
            return call_user_func_array(array($object, $method), $parameters);
        }
    }


    /**
     * Redirects to the given URL.
     *
     * @param string $url
     * @param integer $status
     */
    public function redirect($url, $status = 302)
    {
        header('Location: '.$url, true, $status);
    }

    /**
     * Renders a view.
     * Adds the current session to the parameters
     *
     * @param string $view
     * @param array $parameters
     */
    public function render($view, $parameters = array())
    {
        $parameters['app'] = array(
            'session' => $this->session
        );
        return $this->twig->render($view, $parameters);
    }

    /**
     * Returns a NotFoundHttpException.
     *
     * @param string $message
     * @param Exception $previous
     */
    public function createNotFoundException($message = 'Not Found', $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param mixed $type
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
     * This is to be used if you want to link to a plugin/controller/action page.
     *
     * @param array $parameters
     * @return string
     */
    public function generateUrl($parameters = array())
    {
        $url = $this->request->getScheme().'://'.$this->request->getHost().$this->request->getBaseUrl();

        if (is_string($parameters)) {
            $action = $parameters;
            $parameters = array('action' => $action);
        }

        if (!isset($parameters['page'])) {
            $parameters['page'] = $this->request->query->get('page');
        }

        return $url.'?'.http_build_query($parameters);
    }
}