<?php
include_once("exceptions.inc");

// Raise exceptions on assert failures
ini_set("assert.exception", 1);

class ApiRouter
{
    private $_url_map = [];
    private $_validators = [];
    private $_response;
    private bool $_raw_response = false;

    public function add_route(string $method, string $url, string $function): void
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

    public function route(string $url, array $query_params)
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
        $this->_response = $function($method, $data, $query_params);
        return $this->_response;
    }

    public function add_validator(string $label, string $function): void
    {
        $this->_validators[$label] = $function;
    }

    private function get_validator(array $url_map): array
    {
        foreach (array_keys($url_map) as $route) {
            if (startswith($route, ":")) {
                return [$route, $this->_validators[$route]];
            }
        }
        throw new InvalidAPI();
    }

    public function request(bool $raw = false)
    {
        if ($raw) {
            return file_get_contents('php://input');
        } else {
            $json_object = json_decode(file_get_contents('php://input'), true);
            if ($json_object === null) {
                throw new InvalidValue("Content was not valid JSON");
            }
            return $json_object;
        }
    }

    public function response(bool $raw = false): string
    {
        if ($raw || $this->_raw_response) {
            return $this->_response;
        } else {
            return json_encode($this->_response, JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    public function set_raw_response(): void
    {
        $this->_raw_response = true;
    }

    public static function get_router(): ApiRouter
    {
        static $router = null;
        if (!$router) {
            $router = new ApiRouter();
        }
        return $router;
    }
}
