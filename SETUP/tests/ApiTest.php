<?php

// test api functions

class ApiTest extends ProjectUtils
{
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

    public function test_get_invalid_round_stats()
    {
        $this->expectExceptionCode(103);

        $path = "v1/stats/site/rounds/P4";
        $query_params = "";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "GET";
        $router->route($path, $query_params);
    }

    public function test_get_invalid_page_data()
    {
        $this->expectExceptionCode(104);

        $project = $this->_create_project();
        $path = "v1/projects/$project->projectid/pages/999.png/pagerounds/P1";
        $query_params = "";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "GET";
        $router->route($path, $query_params);
    }

    public function test_get_invalid_pageround_data()
    {
        $this->expectExceptionCode(105);

        $project = $this->_create_project();
        $this->add_page($project, "001");
        // P0 is not a valid round
        $path = "v1/projects/$project->projectid/pages/001.png/pagerounds/P0";
        $query_params = "";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "GET";
        $router->route($path, $query_params);
    }

    public function test_get_valid_pageround_data()
    {
        $project = $this->_create_project();
        $this->add_page($project, "001");
        $path = "v1/projects/$project->projectid/pages/001.png/pagerounds/OCR";
        $query_params = "";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "GET";
        $result = $router->route($path, $query_params);
        $this->assertEquals("001.png", $result["pagename"]);
        $this->assertEquals("{$project->url}/001.png", $result["image_url"]);
        $this->assertEquals($this->TEST_TEXT, $result["text"]);
        $this->assertEquals("P1.page_avail", $result["state"]);
    }

    public function test_create_project_unauthorised()
    {
        $this->expectExceptionCode(3);

        $path = "v1/projects";
        $query_params = "";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "POST";
        $router->route($path, $query_params);
    }

    public function test_create_project_no_data()
    {
        global $pguser;
        $this->expectExceptionCode(100);

        $pguser = $this->TEST_USERNAME_PM;
        $path = "v1/projects";
        $query_params = "";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "POST";
        $router->route($path, $query_params);
    }
}

// this mocks the function in index.php
function api_get_request_body()
{
    global $request_body;
    return $request_body;
}
