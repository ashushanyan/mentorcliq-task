<?php

/**
 *
 * This file is part of mvc-rest-api for PHP.
 *
 */
namespace System\Router;

use http\Env\Request;
use mysql_xdevapi\BaseResult;
use function Sodium\version_string;

class Router {

    /**
     * route list.
     * 
     * @var array
     */
    private $router = [];

    /**
     * match route list.
     * 
     * @var array
     */
    private $matchRouter = [];

    /**
     * request url.
     * 
     * @var string
     */
    private $url;

    /**
     * request http method.
     * 
     * @var string
     */
    private $method;

    /**
     * param list for route pattern
     * 
     * @var array
     */
    private $data = [];

    /**
     *  Response Class
     */
    private $response;

    /**
     *  constaruct
     */
    public function __construct(string $url, string $method) {
        $this->url = rtrim($url, '/');
        $this->method = $method;

        // get response class of $GLOBALS var
        $this->response = $GLOBALS['response'];
    }

    /**
     *  set get request http method for route
     */
    public function get($pattern, $callback) {
        $this->addRoute("GET", $pattern, $callback);
    }

    /**
     *  set post request http method for route
     */
    public function post($pattern, $callback) {
        $this->addRoute('POST', $pattern, $callback);
    }

    /**
     *  set put request http method for route
     */
    public function put($pattern, $callback) {
        $this->addRoute('PUT', $pattern, $callback);
    }

    /**
     *  set delete request http method for route
     */
    public function delete($pattern, $callback) {
        $this->addRoute('DELETE', $pattern, $callback);
    }

    /**
     *  add route object into router var
     */
    public function addRoute($method, $pattern, $callback) {
        array_push($this->router, new Route($method, $pattern, $callback));
    }

    /**
     *  filter requests by http method
     */
    private function getMatchRoutersByRequestMethod() {
        foreach ($this->router as $value) {
            if (strtoupper($this->method) == $value->getMethod())
                array_push($this->matchRouter, $value);
        }
    }

    private function getMatchRoutersByMethod($routers)
    {
        $this->matchRouter = [];
        foreach ($routers as $value) {
            if ($this->dispatch($this->url, $value->getPattern()))
                array_push($this->matchRouter, $value);
        }
    }

    public function dispatch($url, $pattern) {
        preg_match_all('@:([\w]+)@', $pattern, $params, PREG_PATTERN_ORDER);

        $patternAsRegex = preg_replace_callback('@:([\w]+)@', [$this, 'convertPatternToRegex'], $pattern);

        if (substr($pattern, -1) === '/' ) {
            $patternAsRegex = $patternAsRegex . '?';
        }
        $patternAsRegex = '@^' . $patternAsRegex . '@';

        // check match request url
        if (preg_match($patternAsRegex, $url)) {
            return true;
        }

        return false;
    }

    /**
     * filter route patterns by url request
     */
    private function setRequestData($pattern)
    {
        $this->matchRouter = [];
        foreach ($pattern as $value) {
            array_push($this->matchRouter, $value);
            $url_components = parse_url("$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            if (isset($url_components['query'])) {
                parse_str($url_components['query'], $params);
                foreach ($params as  $key => $param) {
                    $this->setData("params", $key, $param);
                }
            }
            $bodyData = file_get_contents('php://input');
            $bodyData = json_decode($bodyData);

            if(isset($bodyData)) {
                foreach ($bodyData as  $key => $param) {
                    $this->setData("body", $key, $param);
                }
            }
        }
    }

    /**
     *  get router
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * set param
     */
    private function setData($type, $key, $value) {
        $this->data[$type][$key] = $value;
    }

    /**
     * Convert Pattern To Regex
     */
    private function convertPatternToRegex($matches) {
        $key = str_replace(':', '', $matches[0]);
        return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
    }

    /**
     *  run application
     */
    public function run() {
        if (!is_array($this->router) || empty($this->router)) 
            throw new Exception('NON-Object Route Set');

        $this->getMatchRoutersByRequestMethod();
        $this->getMatchRoutersByMethod($this->matchRouter);
        $this->setRequestData($this->matchRouter);
        if (!$this->matchRouter || empty($this->matchRouter)) {
			$this->sendNotFound();
		} else {
            // call to callback method
            if (is_callable($this->matchRouter[0]->getCallback()))
                call_user_func($this->matchRouter[0]->getCallback(), $this->data);
            else
                $this->runController($this->matchRouter[0]->getCallback(), new \System\Http\Request());
        }
    }

    /**
     * run as controller
     */
    private function runController($controller, $params) {
        $parts = explode('@', $controller);
        $file =   CONTROLLERS . ucfirst($parts[0])  . '.php';

        if (file_exists($file)) {
            require_once($file);

            // controller class
            $controller = ucfirst($parts[0]);

            if (class_exists($controller))
                $controller = new $controller();
            else
				$this->sendNotFound();

            // set function in controller
            if (isset($parts[1])) {
                $method = $parts[1];
				
                if (!method_exists($controller, $method))
                    $this->sendNotFound();
				
            } else {
                $method = 'index';
            }


            // call to controller
            if (is_callable([$controller, $method])) {
                return call_user_func([$controller, $method], $params);
            }
            else
				$this->sendNotFound();
        }
    }
	
	private function sendNotFound() {
		$this->response->sendStatus(404);
		$this->response->setContent(['error' => 'Sorry This Route Not Found !', 'status_code' => 404]);
	}
}
