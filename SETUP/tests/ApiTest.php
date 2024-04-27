<?php

// test api functions

class ApiTest extends ProjectUtils
{
    //---------------------------------------------------------------------------
    // helper functions

    protected function checkout(string $projectid, string $project_state): array
    {
        $path = "v1/projects/$projectid/checkout";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "PUT";
        return $router->route($path, ['state' => $project_state]);
    }

    protected function resume(string $projectid, string $project_state, string $page_name, string $page_state): array
    {
        return $this->api_project_page($projectid, $project_state, $page_name, $page_state, [], "resume");
    }

    protected function return_to_round(string $projectid, string $project_state, string $page_name, string $page_state): void
    {
        $this->api_project_page($projectid, $project_state, $page_name, $page_state, [], "abandon");
    }

    protected function save_as_in_progress(string $projectid, string $project_state, string $page_name, string $page_state, string $text): array
    {
        return $this->api_project_page($projectid, $project_state, $page_name, $page_state, ["text" => $text], "save");
    }

    protected function save_revert(string $projectid, string $project_state, string $page_name, string $page_state, string $text): array
    {
        return $this->api_project_page($projectid, $project_state, $page_name, $page_state, ["text" => $text], "revert");
    }

    protected function save_as_done(string $projectid, string $project_state, string $page_name, string $page_state, string $text): array
    {
        return $this->api_project_page($projectid, $project_state, $page_name, $page_state, ["text" => $text], "checkin");
    }

    protected function get_text(string $projectid, string $project_state, string $page_name, string $page_state): array
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $path = "v1/projects/$projectid/pages/$page_name";
        $router = ApiRouter::get_router();
        return $router->route($path, ['state' => $project_state, 'pagestate' => $page_state]);
    }

    private function api_project_page(string $projectid, string $project_state, string $page_name, string $page_state, array $request_array = [], string $action)
    {
        global $request_body;

        $_SERVER["REQUEST_METHOD"] = "PUT";
        $request_body = $request_array;
        $path = "v1/projects/$projectid/pages/$page_name";
        $router = ApiRouter::get_router();
        return $router->route($path, ['state' => $project_state, 'pagestate' => $page_state, 'pageaction' => $action]);
    }

    protected function validate_text(string $projectid, string $text): array
    {
        global $request_body;

        $request_body = ["text" => $text];
        $_SERVER["REQUEST_METHOD"] = "PUT";
        $path = "v1/projects/$projectid/validatetext";
        $router = ApiRouter::get_router();
        return $router->route($path, []);
    }

    //---------------------------------------------------------------------------
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

    public function test_get_page_data()
    {
        $project = $this->_create_available_project();
        $_SERVER["REQUEST_METHOD"] = "GET";
        $path = "v1/projects/$project->projectid/pages/001.png";
        $query_params = [];
        $router = ApiRouter::get_router();
        $result = $router->route($path, $query_params);
        $this->assertEquals($this->TEST_TEXT, $result["text"]);
        $this->assertEquals("P1.page_avail", $result["pagestate"]);
    }

    //---------------------------------------------------------------------------
    // tests for proofreading

    public function test_checkout_bad_projectid()
    {
        global $pguser;
        $pguser = $this->TEST_USERNAME;

        $this->expectExceptionCode(101);

        $this->checkout("projectId", "P1.proj_avail");
    }

    public function test_checkout_invalid_proj_state()
    {
        global $pguser;
        $pguser = $this->TEST_USERNAME;

        $this->expectExceptionCode(6);

        $project = $this->_create_project();
        $this->checkout($project->projectid, "project_ne");
    }

    public function test_project_no_state_given()
    {
        global $pguser;

        $this->expectExceptionCode(6);

        $project = $this->_create_project();
        $pguser = $this->TEST_USERNAME;

        $path = "v1/projects/$project->projectid/checkout";
        $router = ApiRouter::get_router();
        $_SERVER["REQUEST_METHOD"] = "PUT";
        $router->route($path, []);
    }

    public function test_checkout_wrong_proj_state()
    {
        global $pguser;
        $pguser = $this->TEST_USERNAME;

        $this->expectExceptionCode(120);

        $project = $this->_create_project();
        $this->checkout($project->projectid, "P1.proj_bad");
    }

    public function test_project_not_in_round()
    {
        global $pguser;

        $this->expectExceptionCode(110);

        $project = $this->_create_project();
        $pguser = $this->TEST_USERNAME;

        $this->checkout($project->projectid, "project_new");
    }

    public function test_project_unavailable()
    {
        global $pguser;

        $this->expectExceptionCode(112);

        $project = $this->_create_project();
        $pguser = $this->TEST_USERNAME;
        $this->quiet_project_transition($project->projectid, PROJ_P1_UNAVAILABLE, $this->TEST_USERNAME_PM);

        $this->checkout($project->projectid, "P1.proj_unavail");
    }

    public function test_user_not_qualified_for_round()
    {
        global $pguser;
        $pguser = $this->TEST_USERNAME;
        // see comment in SettingsClass.inc about calling by reference
        $settings = & Settings::get_settings($this->TEST_USERNAME);
        $settings->set_boolean("P2.access", false);

        $this->expectExceptionCode(302);

        $project = $this->_create_available_project();
        $this->advance_to_round2($project->projectid);

        $this->checkout($project->projectid, "P2.proj_avail");
    }

    public function test_project_checkout_available()
    {
        global $pguser;

        $project = $this->_create_available_project();
        $pguser = $this->TEST_USERNAME;

        $response = $this->checkout($project->projectid, "P1.proj_avail");
        $expected = [
            'pagename' => '001.png',
            'image_url' => "{$project->url}/001.png",
            'text' => $this->TEST_TEXT,
            'pagestate' => "P1.page_out",
            'saved' => false,
            'pagenum' => '001',
            'round_info' => [],
            'language_direction' => 'LTR',
        ];
        $this->assertEquals($expected, $response);
    }

    public function test_many_pages_few_days()
    {
        // user done many pages but few days on site
        // $page_tally_threshold 500 for new projects in reserve time

        global $pguser;
        $project = $this->_create_available_project();
        $pguser = $this->TEST_USERNAME;
        page_tallies_add("P1", $pguser, 501);
        $response = $this->checkout($project->projectid, "P1.proj_avail");
        $this->assertEquals('001.png', $response['pagename']);
    }

    public function test_few_pages_many_days()
    {
        global $pguser;
        $project = $this->_create_available_project();
        $pguser = $this->TEST_OLDUSERNAME;
        $response = $this->checkout($project->projectid, "P1.proj_avail");
        $this->assertEquals('001.png', $response['pagename']);
    }

    public function test_many_pages_many_days()
    {
        global $pguser;
        $project = $this->_create_available_project();
        $pguser = $this->TEST_OLDUSERNAME;
        page_tallies_add("P1", $pguser, 501);

        // reserved for new proofreaders
        $this->expectExceptionCode(306);
        $this->checkout($project->projectid, "P1.proj_avail");
    }

    public function test_beginner_project_checkout()
    {
        global $pguser;

        $this->valid_project_data["difficulty"] = "beginner";
        $project = $this->_create_available_project();
        $pguser = $this->TEST_USERNAME;
        // beginners limit is 40 in user_is.inc
        page_tallies_add("P1", $pguser, 50);

        $this->expectExceptionCode(303);
        $this->checkout($project->projectid, "P1.proj_avail");
    }

    public function test_beginner_mentor_project_checkout()
    {
        global $pguser;

        $this->expectExceptionCode(305);

        $this->valid_project_data["difficulty"] = "beginner";
        $project = $this->_create_available_project();
        $this->advance_to_round2($project->projectid);
        $pguser = $this->TEST_USERNAME;
        $settings = & Settings::get_settings($this->TEST_USERNAME);
        $settings->set_boolean("P2.access", true);

        $this->checkout($project->projectid, "P2.proj_avail");
    }

    public function test_resume_page()
    {
        global $pguser;

        $project = $this->_create_available_project();
        $pguser = $this->TEST_USERNAME;

        $this->checkout($project->projectid, "P1.proj_avail");
        $response = $this->resume($project->projectid, 'P1.proj_avail', '001.png', 'P1.page_out');
        $expected =
        [
            'pagename' => '001.png',
            'image_url' => "{$project->url}/001.png",
            'text' => $this->TEST_TEXT,
            'pagestate' => "P1.page_out",
            'saved' => false,
            'pagenum' => '001',
            'round_info' => [],
            'language_direction' => 'LTR',
        ];

        $this->assertEquals($expected, $response);
    }

    public function test_resume_page_not_owned()
    {
        global $pguser;

        $this->expectExceptionCode(307);

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();

        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        // let another user try to resume it
        $pguser = $this->TEST_USERNAME_PM;
        $this->resume($project->projectid, 'P1.proj_avail', '001.png', 'P1.page_out');
    }

    public function test_project_return_to_round()
    {
        global $pguser;

        $project = $this->_create_available_project();
        $pguser = $this->TEST_USERNAME;

        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        // return it to round
        $response = $this->return_to_round($project->projectid, 'P1.proj_avail', '001.png', 'P1.page_out');
        $this->assertEquals(null, $response);
    }

    public function test_return_page_no_state()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();

        $this->expectExceptionCode(6);

        $_SERVER["REQUEST_METHOD"] = "PUT";
        $path = "v1/projects/$project->projectid/pages/001.png";
        $router = ApiRouter::get_router();
        $router->route($path, ['state' => "P1.proj_avail", 'pageaction' => "abandon"]);
    }

    public function test_no_page_action()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();
        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        $this->expectExceptionCode(2);

        $_SERVER["REQUEST_METHOD"] = "PUT";
        $path = "v1/projects/$project->projectid/pages/001.png";
        $router = ApiRouter::get_router();
        $router->route($path, ['state' => "P1.proj_avail", 'pagestate' => "P1.page_out"]);
    }

    public function test_invalid_page_action()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();
        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        $this->expectExceptionCode(2);

        $_SERVER["REQUEST_METHOD"] = "PUT";
        $path = "v1/projects/$project->projectid/pages/001.png";
        $router = ApiRouter::get_router();
        $router->route($path, ['state' => "P1.proj_avail", 'pagestate' => "P1.page_out", 'pageaction' => "revoke"]);
    }

    public function test_return_non_existent_page()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();

        $this->expectExceptionCode(104);
        $this->return_to_round($project->projectid, "P1.proj_avail", "002.png", "P1.page_out");
    }

    public function test_return_invalid_page_state()
    {
        global $pguser;

        $this->expectExceptionCode(6);

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();

        $this->return_to_round($project->projectid, "P1.proj_avail", "001.png", "P1.page_in");
    }

    public function test_return_page_state_not_as_in_project()
    {
        global $pguser;

        $this->expectExceptionCode(119);

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();

        $this->return_to_round($project->projectid, "P1.proj_avail", "001.png", "P1.page_out");
    }

    public function test_return_page_state_not_allowed()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();
        $this->checkout($project->projectid, "P1.proj_avail");

        $this->return_to_round($project->projectid, "P1.proj_avail", "001.png", "P1.page_out");

        $this->expectExceptionCode(115);
        // user still appears to own this page. Can't return from available.
        $this->return_to_round($project->projectid, "P1.proj_avail", "001.png", "P1.page_avail");
    }

    public function test_return_page_not_owned()
    {
        global $pguser;

        $this->expectExceptionCode(307);

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();

        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        // let another user return it to round
        $pguser = $this->TEST_USERNAME_PM;
        $this->return_to_round($project->projectid, "P1.proj_avail", "001.png", "P1.page_out");
    }

    public function test_save_as_in_progress()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();

        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        // save as in progress
        $response = $this->save_as_in_progress($project->projectid, "P1.proj_avail", "001.png", "P1.page_out", $this->TEST_MODIFIED_TEXT);
        $expected = [
            "text" => $this->TEST_MODIFIED_TEXT,
            'pagestate' => "P1.page_temp",
            'saved' => true,
        ];

        $this->assertEquals($expected, $response);

        // get original text back
        $response = $this->save_revert($project->projectid, "P1.proj_avail", "001.png", "P1.page_temp", $this->TEST_MODIFIED_TEXT);
        $this->assertEquals($this->TEST_TEXT, $response["text"]);

        // get last saved text
        $response = $this->resume($project->projectid, "P1.proj_avail", "001.png", "P1.page_temp");
        $this->assertEquals($this->TEST_MODIFIED_TEXT, $response["text"]);

        // save invalid text
        $this->expectExceptionCode(125);

        $this->save_as_in_progress($project->projectid, "P1.proj_avail", "001.png", "P1.page_temp", "This is a bĀd test file");
    }

    public function test_save_no_text()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project();

        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        // save as in progress
        $this->expectExceptionCode(6);
        $this->api_project_page($project->projectid, "P1.proj_avail", "001.png", "P1.page_out", [], "save");
    }

    public function test_save_as_done()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project(2);

        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        // get user's tally
        $user = new User($pguser);
        $user_tallyboard = new TallyBoard("P1", 'U');
        $tally = $user_tallyboard->get_current_tally($user->u_id);

        // save as done
        $response = $this->save_as_done($project->projectid, "P1.proj_avail", "001.png", "P1.page_out", $this->TEST_MODIFIED_TEXT);
        $this->assertEquals(0, $response["status"]);

        // check tally has increased by one
        $this->assertEquals(1, $user_tallyboard->get_current_tally($user->u_id) - $tally);

        // reopen
        $response = $this->resume($project->projectid, "P1.proj_avail", "001.png", "P1.page_saved");
        $expected =
        [
            'pagename' => '001.png',
            'image_url' => "{$project->url}/001.png",
            'text' => $this->TEST_MODIFIED_TEXT,
            'pagestate' => "P1.page_temp",
            'saved' => true,
            'pagenum' => '001',
            'round_info' => [],
            'language_direction' => 'LTR',
        ];

        $this->assertEquals($expected, $response);
        // check tally back to original value
        $this->assertEquals($user_tallyboard->get_current_tally($user->u_id), $tally);
    }

    public function test_daily_limit()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_available_project(2);

        // check out a page
        $this->checkout($project->projectid, "P1.proj_avail");

        $round = get_Round_for_round_id("P1");
        $old_limit = $round->daily_page_limit;
        $round->daily_page_limit = 1;
        // check reaching limit
        $response = $this->save_as_done($project->projectid, "P1.proj_avail", '001.png', "P1.page_out", $this->TEST_MODIFIED_TEXT);
        $this->assertEquals(1, $response["status"]);

        // checkout another page
        $this->checkout($project->projectid, "P1.proj_avail");
        $this->expectExceptionCode(308);
        $this->save_as_done($project->projectid, "P1.proj_avail", '002.png', "P1.page_out", $this->TEST_MODIFIED_TEXT);

        // reset daily limit
        $round->daily_page_limit = $old_limit;
    }

    public function test_info_for_previous_proofreaders()
    {
        global $pguser;

        $project = $this->_create_available_project();
        $pguser = $this->TEST_USERNAME;
        $this->checkout($project->projectid, "P1.proj_avail");
        $this->save_as_done($project->projectid, "P1.proj_avail", '001.png', "P1.page_out", $this->TEST_MODIFIED_TEXT);

        $this->advance_to_round2($project->projectid);
        $pguser = $this->TEST_OLDUSERNAME;
        $settings = & Settings::get_settings($this->TEST_OLDUSERNAME);
        $settings->set_boolean("P2.access", true);
        $response = $this->checkout($project->projectid, "P2.proj_avail");
        $info = $response['round_info'];
        $this->assertEquals(1, count($info));
        $this->assertEquals('P1', $info[0]->round_id);
        $this->assertEquals($this->TEST_USERNAME, $info[0]->username);

        $this->save_as_done($project->projectid, "P2.proj_avail", '001.png', "P2.page_out", $this->TEST_MODIFIED_TEXT);

        $this->advance_to_round3($project->projectid);
        $pguser = $this->TEST_USERNAME_PM;
        $settings = & Settings::get_settings($this->TEST_USERNAME_PM);
        $settings->set_boolean("P3.access", true);
        $response = $this->checkout($project->projectid, "P3.proj_avail");
        $info = $response['round_info'];
        $this->assertEquals(2, count($info));
        $this->assertEquals('P1', $info[0]->round_id);
        $this->assertEquals($this->TEST_USERNAME, $info[0]->username);
        $this->assertEquals('P2', $info[1]->round_id);
        $this->assertEquals($this->TEST_OLDUSERNAME, $info[1]->username);
    }

    public function test_validate_text()
    {
        $project = $this->_create_available_project();
        $response = $this->validate_text($project->projectid, "This is an invĀlid test file");
        $expected = [
            'valid' => false,
            'mark_array' => [["This is an inv", 0], ["Ā", 1], ["lid test file", 0]],
        ];
        $this->assertEquals($expected, $response);

        $response = $this->validate_text($project->projectid, "This is a valid test file");
        $expected = [
            'valid' => true,
            'mark_array' => [["This is a valid test file", 0]],
        ];
        $this->assertEquals($expected, $response);
    }
}

// this mocks the function in index.php
function api_get_request_body()
{
    global $request_body;
    return $request_body;
}
