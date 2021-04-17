<?php
include_once($relPath."misc.inc"); // startswith()
include_once("exceptions.inc");

// Raise exceptions on assert failures
ini_set("assert.exception", 1);

class ApiRouter
{
    private $_url_map = [];
    private $_validators = [];

    public function add_route($method, $url, $function)
    {
        // Confirm the function is defined or raise an assert exception
        assert(function_exists($function), "$function not defined");

        $url_map = &$this->_url_map;
        $parts = explode("/", $url);
        foreach ($parts as $part) {
            // If this is a param placeholder, confirm there is a validator
            // for it.
            if (startswith($part, ":")) {
                assert(
                    isset($this->_validators[$part]),
                    "No validator specified for $part"
                );
            }
            if (!isset($url_map[$part])) {
                $url_map[$part] = [];
            }
            $url_map = &$url_map[$part];
        }
        $url_map["endpoint"][$method] = $function;
    }

    public function route($url, $query_params)
    {
        $url_map = &$this->_url_map;
        $data = [];
        $parts = explode("/", $url);
        foreach ($parts as $part) {
            if (isset($url_map[$part])) {
                $url_map = &$url_map[$part];
            } else {
                [$param_name, $validator] = $this->get_validator($url_map);
                $url_map = &$url_map[$param_name];
                $data[$param_name] = $validator($part, $data);
            }
        }
        if (!isset($url_map["endpoint"])) {
            throw new InvalidAPI();
        }
        $method = $_SERVER["REQUEST_METHOD"];
        if (!isset($url_map["endpoint"][$method])) {
            throw new MethodNotAllowed();
        }
        $function = $url_map["endpoint"][$method];
        return $function($method, $data, $query_params);
    }

    public function add_validator($label, $function)
    {
        $this->_validators[$label] = $function;
    }

    private function get_validator($url_map)
    {
        foreach (array_keys($url_map) as $route) {
            if (startswith($route, ":")) {
                return [$route, $this->_validators[$route]];
            }
        }
        throw new InvalidAPI();
    }

    public static function get_router()
    {
        static $router = null;
        if (!$router) {
            $router = new ApiRouter();
        }
        return $router;
    }
}
