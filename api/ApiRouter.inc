<?php
include_once("exceptions.inc");

// Raise exceptions on assert failures
ini_set("assert.exception", 1);

/**
 * We use a trie to match the path to its handler(s).
 *
 * Handlers contains the individual handlers, keyed by the method.
 */
class TrieNode
{
    /** @var array<string, TrieNode> */
    public array $children;
    /** @var array<string, callable> */
    public array $handlers;
}

class ApiRouter
{
    private TrieNode $root;
    /** @var array<string, callable> */
    private $_validators;
    /** @var mixed */
    private $_response;
    private bool $_raw_response = false;

    public function __construct()
    {
        $this->root = new TrieNode();
        $this->_validators = [];
    }

    public function add_route(string $method, string $url, callable $function): void
    {
        $node = $this->root;
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
            if (!isset($node->children[$part])) {
                $node->children[$part] = new TrieNode();
            }
            $node = $node->children[$part];
        }
        $node->handlers[$method] = $function;
    }

    /** @return mixed */
    public function route(string $url, array $query_params)
    {
        $node = $this->root;
        $data = [];
        $parts = explode("/", $url);
        foreach ($parts as $part) {
            $next_node = $node->children[$part] ?? null;
            if ($next_node) {
                $node = $next_node;
            } else {
                [$param_name, $validator] = $this->get_validator($node);
                $node = $node->children[$param_name];
                $data[$param_name] = $validator($part, $data);
            }
        }
        if (empty($node->handlers)) {
            throw new InvalidAPI();
        }
        $method = $_SERVER["REQUEST_METHOD"];
        $handler = $node->handlers[$method] ?? null;
        if (!$handler) {
            throw new MethodNotAllowed();
        }
        $this->_response = $handler($method, $data, $query_params);
        return $this->_response;
    }

    public function add_validator(string $label, callable $function): void
    {
        $this->_validators[$label] = $function;
    }

    /** @return array{0: string, 1: callable} */
    private function get_validator(TrieNode $node): array
    {
        foreach (array_keys($node->children) as $route) {
            if (startswith($route, ":")) {
                return [$route, $this->_validators[$route]];
            }
        }
        throw new InvalidAPI();
    }

    /** @return mixed */
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
        /** @var ?ApiRouter */
        static $router = null;
        if (!$router) {
            $router = new ApiRouter();
        }
        return $router;
    }
}
