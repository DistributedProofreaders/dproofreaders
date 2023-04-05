<?php

// api functions in api/v1_projects.inc

class ApiTest extends PHPUnit\Framework\TestCase
{
    private $TEST_USERNAME_PM = "ProjectTestPM_php";
    private $TEST_USERNAME = "ProjectTest_php";
    private $TEST_USERNAME_2 = "ProjectTest_2_php";
    private $TEST_OLDUSERNAME = "ProjectTest_old_php";
    private $TEST_IMAGESOURCE = "PrjTest";
    private $valid_projectID = "projectID45c225f598e32";
    private $valid_page_image = "001.png";
    private $valid_project_data = [
        "nameofwork" => "War and Peace",
        "authorsname" => "Bob Smith",
        "language" => "English",
        "genre" => "Other",
        "difficulty" => "average",
        "clearance" => "123abc",
        // username and image_source are set in setUp() after creation
    ];
    private $created_projectids = [];

    private $TEST_IMAGE_FILE_NAME = "001.png";
    private $TEST_IMAGE_FILE_NAME_2 = "002.png";
    private $TEST_TEXT = "This is a test file";
    private $TEST_MODIFIED_TEXT = "This is a modified test file";
    private $TEST_BAD_TEXT = "This is a bĀd test file";

    protected function setUp(): void
    {
        create_test_user($this->TEST_USERNAME_PM);

        // make the user a PM
        $settings = new Settings($this->TEST_USERNAME_PM);
        $settings->set_boolean("manager", true);

        create_test_user($this->TEST_USERNAME);

        // create an old user (new users $days_on_site_threshold = 21)
        create_test_user($this->TEST_OLDUSERNAME, 22);

        create_test_image_source($this->TEST_IMAGESOURCE);

        $this->valid_project_data["username"] = $this->TEST_USERNAME_PM;
        $this->valid_project_data["image_source"] = $this->TEST_IMAGESOURCE;
    }

    protected function tearDown(): void
    {
        // clean up the PM value
        $settings = new Settings($this->TEST_USERNAME_PM);
        $settings->set_value("manager", null);

        delete_test_user($this->TEST_USERNAME_PM);
        delete_test_user($this->TEST_USERNAME);
        delete_test_user($this->TEST_OLDUSERNAME);
        delete_test_image_source($this->TEST_IMAGESOURCE);

        foreach ($this->created_projectids as $projectid) {
            $project = new Project($projectid);
            delete_test_project_remains($project);
        }
    }

    // -----------------------------------------------------------
    // helper functions

    protected function _create_project()
    {
        $project = new Project();
        foreach ($this->valid_project_data as $key => $value) {
            $project->$key = $value;
        }
        $project->save();
        $this->created_projectids[] = $project->projectid;

        return $project;
    }

    protected function add_page($project, $base)
    {
        $source_project_dir = sys_get_temp_dir();
        $txt_file_name = "$base.txt";
        $txt_file_path = "$source_project_dir/$txt_file_name";
        $fp = fopen($txt_file_path, 'w');
        fwrite($fp, $this->TEST_TEXT);
        fclose($fp);

        $image_file_name = "$base.png";
        $img_file_path = "{$project->dir}/$image_file_name";
        $fp = fopen($img_file_path, 'w');
        fwrite($fp, str_repeat("This is a test image file", 10));
        fclose($fp);

        project_add_page($project->projectid, $base, $image_file_name, $txt_file_path, $this->TEST_USERNAME_PM, time());
    }
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
        $path = "v1/projects/$project->projectid/pages/001.png/pagerounds/P0";
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
