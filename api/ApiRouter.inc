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
        return $handler($method, $data, $query_params);
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
