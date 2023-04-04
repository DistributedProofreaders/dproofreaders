<?php

// api functions in api/v1_projects.inc

class ApiTest extends PHPUnit\Framework\TestCase
{
    // ----------------------------------------------------
    // tests

    public function test_get_invalid_project_info()
    {
        $this->expectExceptionCode(101);

        $projectid = "1234";
        $path = "v1/projects/$projectid";
        $query_params = "";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "GET";
        $router->route($path, $query_params);
    }
}

// this mocks the function in index.php
function api_get_request_body()
{
    global $request_body;
    return $request_body;
}
