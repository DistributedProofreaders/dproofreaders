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

    protected function _create_available_project_with_page($npage = 1)
    {
        $project = $this->_create_project();
        $project->add_charsuite("basic-latin");

        $this->add_page($project, "001");
        if ($npage > 1) {
            $this->add_page($project, "002");
        }

        project_transition($project->projectid, PROJ_P1_UNAVAILABLE, $this->TEST_USERNAME_PM);
        project_transition($project->projectid, PROJ_P1_WAITING_FOR_RELEASE, $this->TEST_USERNAME_PM);
        project_transition($project->projectid, PROJ_P1_AVAILABLE, PT_AUTO);

        return $project;
    }

    protected function _create_available_project_round2()
    {
        $project = $this->_create_available_project_with_page();
        project_transition($project->projectid, PROJ_P1_COMPLETE, PT_AUTO);
        project_transition($project->projectid, PROJ_P1_COMPLETE, PT_AUTO);
        project_transition($project->projectid, PROJ_P2_WAITING_FOR_RELEASE, PT_AUTO);
        project_transition($project->projectid, PROJ_P2_AVAILABLE, PT_AUTO);

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

    protected function checkout($roundid, $projectid)
    {
        // check out a page
        $path = "v1/rounds/$roundid/projects/$projectid/pages";
        $query_params = "";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "POST";
        return $router->route($path, $query_params);
    }

    protected function return_to_round($projectid, $page)
    {
        global $request_body;

        $path = "v1/rounds/P1/projects/$projectid/pages/$page";
        $query_params = "";
        $router = ApiRouter::get_router();
        $request_body = ["new_page_state" => "avail"];
        $_SERVER["REQUEST_METHOD"] = "PUT";
        return $router->route($path, $query_params);
    }

    protected function save_as_done($projectid, $page, $text)
    {
        global $request_body;

        $path = "v1/rounds/P1/projects/{$projectid}/pages/$page";
        $query_params = "";
        $router = ApiRouter::get_router();
        $request_body = ["new_page_state" => "saved", "text" => $text];
        $_SERVER["REQUEST_METHOD"] = "PUT";
        return $router->route($path, $query_params);
    }

    protected function get_text($projectid, $page, $option)
    {
        $path = "v1/rounds/P1/projects/{$projectid}/pages/$page";
        $query_params = ["pageoption" => $option];
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "GET";
        return $router->route($path, $query_params);
    }

    // ----------------------------------------------------
    // tests

    public function test_checkout_invalid_round()
    {
        global $pguser;

        $this->expectExceptionCode(DP::INVALID_ROUND);

        $this->checkout("P0", "projectId");
    }

    public function test_checkout_bad_projectid()
    {
        global $pguser;

        $this->expectExceptionCode(DP::NO_SUCH_PROJECT_ID);

        $this->checkout("P1", "projectId");
    }

    public function test_user_access_denied()
    {
        global $pguser;

        $this->expectExceptionCode(DP::USER_NOT_QUALIFIED_FOR_ROUND);

        $project = $this->_create_project();
        $pguser = $this->TEST_USERNAME;

        $this->checkout("P3", $project->projectid);
    }

    public function test_project_not_in_round()
    {
        global $pguser;

        $this->expectExceptionCode(DP::PROJECT_NOT_IN_ROUND);

        $project = $this->_create_project();
        $pguser = $this->TEST_USERNAME;

        $this->checkout("P1", $project->projectid);
    }

    public function test_project_unavailable()
    {
        global $pguser;

        $this->expectExceptionCode(DP::PROJECT_NOT_AVAILABLE);

        $project = $this->_create_project();
        $pguser = $this->TEST_USERNAME;
        project_transition($project->projectid, PROJ_P1_UNAVAILABLE, $this->TEST_USERNAME_PM);

        $this->checkout("P1", $project->projectid);
    }

    public function test_project_checkout_available()
    {
        global $pguser, $ELR_round;

        // new project, new user
        $project = $this->_create_available_project_with_page();
        $pguser = $this->TEST_USERNAME;

        $response = $this->checkout("P1", $project->projectid);
        $expected = [
            'pagename' => $this->TEST_IMAGE_FILE_NAME,
            'image_url' => "{$project->url}/{$this->TEST_IMAGE_FILE_NAME}",
            'text' => $this->TEST_TEXT,
        ];
        $this->assertEquals($response, $expected);

        $page = $this->TEST_IMAGE_FILE_NAME;
        $this->return_to_round($project->projectid, $page);

        // user done many pages but few days on site
        // $page_tally_threshold 500 for new projects in reserve time
        page_tallies_add($ELR_round->id, $pguser, 501);
        $response = $this->checkout("P1", $project->projectid);
        $this->assertEquals($response, $expected);
        $this->return_to_round($project->projectid, $page);

        // few pages, many days on site
        $pguser = $this->TEST_OLDUSERNAME;
        $response = $this->checkout("P1", $project->projectid);
        $this->assertEquals($response, $expected);
        $this->return_to_round($project->projectid, $page);

        // user done many pages, many days on site
        $this->expectExceptionCode(DP::RESERVED_FOR_NEW_PROOFREADERS);
        page_tallies_add($ELR_round->id, $pguser, 501);
        $this->checkout("P1", $project->projectid);
    }

    public function test_beginner_project_checkout()
    {
        global $pguser, $ELR_round;

        $this->expectExceptionCode(DP::BEGINNERS_QUOTA_REACHED);

        $this->valid_project_data["difficulty"] = "beginner";
        $project = $this->_create_available_project_with_page();
        $pguser = $this->TEST_USERNAME;
        // beginners limit is 40 in user_is.inc
        page_tallies_add($ELR_round->id, $pguser, 50);

        $this->checkout("P1", $project->projectid);
    }

    public function test_beginner_mentor_project_checkout()
    {
        global $pguser, $ELR_round;

        $this->expectExceptionCode(DP::NO_ACCESS_TO_MENTORS_ONLY);

        $this->valid_project_data["difficulty"] = "beginner";
        $project = $this->_create_available_project_with_page();
        $project = $this->_create_available_project_round2();
        $pguser = $this->TEST_USERNAME;
        $user = new User($pguser);
        $user->grant_access("P2", $pguser);

        $this->checkout("P2", $project->projectid);
    }

    public function test_project_checkout_wrong_round()
    {
        global $pguser;

        $this->expectExceptionCode(DP::PROJECT_NOT_IN_REQUIRED_ROUND);

        $project = $this->_create_available_project_with_page();
        $pguser = $this->TEST_USERNAME;
        $user = new User($pguser);
        $user->grant_access("P2", $pguser);

        $this->checkout("P2", $project->projectid);
    }

    public function test_project_checkout_no_more_pages()
    {
        global $pguser;

        $this->expectExceptionCode(DP::NO_MORE_PAGES);

        $project = $this->_create_available_project_with_page();
        $pguser = $this->TEST_USERNAME;

        $this->checkout("P1", $project->projectid);
        // try to get another page
        $this->checkout("P1", $project->projectid);
    }

    public function test_return_to_round()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project_with_page();

        // check out a page
        $response = $this->checkout("P1", $project->projectid);

        // return it to round
        $page = $response['pagename'];
        $response = $this->return_to_round($project->projectid, $page);
        $this->assertEquals($response, null);
    }

    public function test_return_non_existent_page()
    {
        global $pguser;

        $this->expectExceptionCode(DP::NO_SUCH_PAGE);

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project_with_page();

        // check out a page
        $this->checkout("P1", $project->projectid);

        // return another page to round
        $this->return_to_round($project->projectid, $this->TEST_IMAGE_FILE_NAME_2);
    }

    public function test_return_page_wrong_state()
    {
        global $pguser;

        $this->expectExceptionCode(DP::WRONG_PAGE_STATE);

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project_with_page();

        // return a page to round without checkout first
        $this->return_to_round($project->projectid, $this->TEST_IMAGE_FILE_NAME);
    }

    public function test_return_page_not_owned()
    {
        global $pguser;

        $this->expectExceptionCode(DP::WRONG_PAGE_OWNER);

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project_with_page();

        // check out a page
        $this->checkout("P1", $project->projectid);

        // let another user return it to round
        $pguser = $this->TEST_USERNAME_2;
        $this->return_to_round($project->projectid, $this->TEST_IMAGE_FILE_NAME);
    }

    public function test_save_as_in_progress()
    {
        global $pguser, $request_body;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project_with_page();

        // check out a page
        $response = $this->checkout("P1", $project->projectid);

        // save as in progress
        $page = $response['pagename'];
        $path = "v1/rounds/P1/projects/{$project->projectid}/pages/$page";
        $query_params = "";
        $router = ApiRouter::get_router();
        $request_body = ["new_page_state" => "temp", "text" => $this->TEST_MODIFIED_TEXT];
        $_SERVER["REQUEST_METHOD"] = "PUT";
        $response = $router->route($path, $query_params);
        $this->assertEquals($response, null);

        // get original text back
        $query_params = ["pageoption" => "original"];
        $_SERVER["REQUEST_METHOD"] = "GET";
        $response = $router->route($path, $query_params);
        $this->assertEquals($response, ["text" => $this->TEST_TEXT]);

        // get last saved text
        $query_params = ["pageoption" => "current"];
        $_SERVER["REQUEST_METHOD"] = "GET";
        $response = $router->route($path, $query_params);
        $this->assertEquals($response, ["text" => $this->TEST_MODIFIED_TEXT]);

        // save bad text
        $query_params = "";
        $request_body = ["new_page_state" => "temp", "text" => $this->TEST_BAD_TEXT];
        $_SERVER["REQUEST_METHOD"] = "PUT";
        $response = $router->route($path, $query_params);
        $this->assertEquals($response, null);

        // get saved text back - bad char removed
        $query_params = ["pageoption" => "current"];
        $_SERVER["REQUEST_METHOD"] = "GET";
        $response = $router->route($path, $query_params);
        $this->assertEquals($response, ["text" => "This is a bd test file"]);
    }

    public function test_invalid_text()
    {
        global $request_body;

        $project = $this->_create_available_project_with_page();
        $query_params = "";
        $router = ApiRouter::get_router();
        $request_body = ["text" => $this->TEST_BAD_TEXT];
        $path = "v1/projects/{$project->projectid}/validatetext";
        $_SERVER["REQUEST_METHOD"] = "POST";
        $response = $router->route($path, $query_params);
        $expected_response = [
            "mark_array" => [["This is a b", 0], ["Ā", 1], ["d test file", 0]],
            "good" => false,
        ];
        $this->assertEquals($response, $expected_response);

        $request_body = ["text" => $this->TEST_TEXT];
        $response = $router->route($path, $query_params);
        $expected_response = [
            "mark_array" => [[$this->TEST_TEXT, 0]],
            "good" => true,
        ];
        $this->assertEquals($response, $expected_response);
    }

    public function test_save_as_done()
    {
        global $pguser, $request_body;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project_with_page();

        // check out a page
        $response = $this->checkout("P1", $project->projectid);

        // save as done
        $page = $response['pagename'];
        $response = $this->save_as_done($project->projectid, $page, $this->TEST_MODIFIED_TEXT);
        $this->assertEquals($response, null);

        $this->expectExceptionCode(DP::WRONG_PAGE_STATE);
        // try to get original text
        $this->get_text($project->projectid, $page, "original");
    }

    public function test_save_as_done_and_reopen()
    {
        global $pguser, $request_body;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project_with_page();

        // check out a page
        $response = $this->checkout("P1", $project->projectid);

        // get user's tally
        $user = new User($pguser);
        $user_tallyboard = new TallyBoard("P1", 'U');
        $tally = $user_tallyboard->get_current_tally($user->u_id);

        // save as done
        $page = $response['pagename'];
        $response = $this->save_as_done($project->projectid, $page, $this->TEST_MODIFIED_TEXT);
        $this->assertEquals($response, null);
        // check tally has increased by one
        $this->assertEquals($user_tallyboard->get_current_tally($user->u_id) - $tally, 1);

        // reopen
        $path = "v1/rounds/P1/projects/{$project->projectid}/pages/$page";
        $query_params = "";
        $_SERVER["REQUEST_METHOD"] = "POST";
        $router = ApiRouter::get_router();
        $request_body = [];
        $response = $router->route($path, $query_params);
        $expected = [
            'pagename' => $this->TEST_IMAGE_FILE_NAME,
            'image_url' => "{$project->url}/{$this->TEST_IMAGE_FILE_NAME}",
            'text' => $this->TEST_MODIFIED_TEXT,
        ];
        $this->assertEquals($response, $expected);
        // check tally back to original value
        $this->assertEquals($user_tallyboard->get_current_tally($user->u_id), $tally);

        // get original text
        $response = $this->get_text($project->projectid, $page, "original");
        $this->assertEquals($response, ["text" => $this->TEST_TEXT]);
    }

    public function test_daily_limit()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project_with_page(2);
        $round = get_Round_for_round_id("P1");
        $round->daily_page_limit = 1;

        // checkout a page
        $response = $this->checkout("P1", $project->projectid);
        $expected = [
            'pagename' => $this->TEST_IMAGE_FILE_NAME,
            'image_url' => "{$project->url}/{$this->TEST_IMAGE_FILE_NAME}",
            'text' => $this->TEST_TEXT,
        ];
        $this->assertEquals($response, $expected);

        // save as done
        $page = $response['pagename'];
        $response = $this->save_as_done($project->projectid, $page, $this->TEST_MODIFIED_TEXT);
        $this->assertEquals($response, null);

        // checkout another page
        $response = $this->checkout("P1", $project->projectid);
        $expected = [
            'pagename' => $this->TEST_IMAGE_FILE_NAME_2,
            'image_url' => "{$project->url}/{$this->TEST_IMAGE_FILE_NAME_2}",
            'text' => $this->TEST_TEXT,
        ];
        $this->assertEquals($response, $expected);

        // save as done
        $page = $response['pagename'];
        try {
            $this->save_as_done($project->projectid, $page, $this->TEST_MODIFIED_TEXT);
        } catch (BadRequest $e) {
            $this->assertEquals(DP::DAILY_LIMIT_EXCEEDED, $e->getCode());
        }
        // check that page has been saved as in progress,
        // if saved as done this will fail with wrong state,
        // if not saved at all the text will not be modified
        $response = $this->get_text($project->projectid, $page, "current");
        $this->assertEquals($response, ["text" => $this->TEST_MODIFIED_TEXT]);
    }
}

// this mocks the function in index.php
function api_get_request_body()
{
    global $request_body;
    return $request_body;
}
