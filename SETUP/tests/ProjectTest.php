<?php

class ProjectTest extends PHPUnit\Framework\TestCase
{
    private $TEST_USERNAME_PM = "ProjectTestPM_php";
    private $TEST_USERNAME = "ProjectTest_php";
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
    private $TEST_TEXT = "This is a test file";

    protected function setUp(): void
    {
        create_test_user($this->TEST_USERNAME_PM);

        // make the user a PM
        $settings = new Settings($this->TEST_USERNAME_PM);
        $settings->set_boolean("manager", true);

        create_test_user($this->TEST_USERNAME);

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
        delete_test_image_source($this->TEST_IMAGESOURCE);

        foreach ($this->created_projectids as $projectid) {
            $project = new Project($projectid);
            delete_test_project_remains($project);
        }
    }

    //------------------------------------------------------------------------

    // helper functions to create a project
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

    protected function _create_project_with_page($npage = 1)
    {
        $project = $this->_create_project();
        $project->add_charsuite("basic-latin");

        $this->add_page($project, "001");
        if ($npage > 1) {
            $this->add_page($project, "002");
        }
        return $project;
    }

    protected function make_project_available($projectid)
    {
        project_transition($projectid, PROJ_P1_UNAVAILABLE, $this->TEST_USERNAME_PM);
        project_transition($projectid, PROJ_P1_WAITING_FOR_RELEASE, $this->TEST_USERNAME_PM);
        project_transition($projectid, PROJ_P1_AVAILABLE, PT_AUTO);
    }

    protected function advance_to_round2($projectid)
    {
        project_transition($projectid, PROJ_P1_COMPLETE, PT_AUTO);
        project_transition($projectid, PROJ_P2_WAITING_FOR_RELEASE, PT_AUTO);
        project_transition($projectid, PROJ_P2_AVAILABLE, PT_AUTO);
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

    // tests
    // Project object save and delete

    public function test_save_create()
    {
        $project = $this->_create_project();

        // test that the project event was created
        $events = load_project_events($project);
        $last_event = array_pop($events);
        $this->assertEquals("creation", $last_event["event_type"]);

        // test that the project was updated in the DB
        $check_project = new Project($project->projectid);
        $this->assertEquals($project->nameofwork, $check_project->nameofwork);

        // confirm the project directory was created
        $this->assertTrue(is_dir($project->dir), "Project dir created: $project->dir");
    }

    public function test_save_update()
    {
        $project = $this->_create_project();

        // update title and save
        $project->nameofwork = "New Name";
        $project->save();

        // test that the project event was created
        $events = load_project_events($project);
        $last_event = array_pop($events);
        $this->assertEquals("edit", $last_event["event_type"]);
        $this->assertEquals("nameofwork", $last_event["details1"]);

        // test that the project was updated in the DB
        $check_project = new Project($project->projectid);
        $this->assertEquals("New Name", $check_project->nameofwork);
    }

    public function test_save_init_from_array()
    {
        $this->expectException(ProjectException::class);
        $project = new Project($this->valid_project_data);
        $project->save();
    }

    public function test_delete()
    {
        $project = $this->_create_project();

        $project->delete();

        // clear PHP's stat cache
        clearstatcache();

        // confirm the project directory was deleted
        $this->assertTrue(!is_dir($project->dir), "Project dir deleted: $project->dir");

        // confirm the project's pages table does not exist
        $this->assertTrue(!does_project_page_table_exist($project->projectid), "Pages table deleted");
    }

    //------------------------------------------------------------------------
    // Language handling functions

    public function test_encode_languages_single()
    {
        $language = Project::encode_languages(["First"]);
        $this->assertEquals("First", $language);
    }

    public function test_encode_languages_single_with_blank()
    {
        $language = Project::encode_languages(["First", ""]);
        $this->assertEquals("First", $language);
    }

    public function test_encode_languages_double()
    {
        $language = Project::encode_languages(["First", "Second"]);
        $this->assertEquals("First with Second", $language);
    }

    public function test_decode_languages_single()
    {
        $languages = Project::decode_language("First");
        $this->assertEquals(["First"], $languages);
    }

    public function test_decode_languages_double()
    {
        $languages = Project::decode_language("First with Second");
        $this->assertEquals(["First", "Second"], $languages);
    }

    public function test_project_languages_setter_single()
    {
        $project = new Project($this->valid_project_data);
        $project->languages = ["First"];
        $this->assertEquals("First", $project->language);
    }

    public function test_project_languages_setter_double()
    {
        $project = new Project($this->valid_project_data);
        $project->languages = ["First", "Second"];
        $this->assertEquals("First with Second", $project->language);
    }

    //------------------------------------------------------------------------
    // Project object validation

    public function test_validate_required_fields_positive_path()
    {
        $project = new Project($this->valid_project_data);
        $errors = $project->validate();
        $this->assertEquals([], $errors);
    }

    public function test_validate_required_fields_negative_path()
    {
        // defaults are not sufficient for validation; test exception raised
        $project = new Project();
        $this->expectException(ProjectException::class);
        $project->validate(true);
    }

    public function test_validate_nameofwork_missing()
    {
        $project = new Project($this->valid_project_data);
        $project->nameofwork = '';
        $errors = $project->validate();
        $this->assertStringContainsString("required", $errors[0]);
    }

    public function test_validate_nameofwork_wrong_type()
    {
        $project = new Project($this->valid_project_data);
        $project->nameofwork = [];
        $errors = $project->validate();
        $this->assertStringContainsString("should be of type", $errors[0]);
    }

    public function test_validate_authorsname_missing()
    {
        $project = new Project($this->valid_project_data);
        $project->authorsname = '';
        $errors = $project->validate();
        $this->assertStringContainsString("required", $errors[0]);
    }

    public function test_validate_pm_missing()
    {
        $project = new Project($this->valid_project_data);
        $project->username = '';
        $errors = $project->validate();
        $this->assertStringContainsString("required", $errors[0]);
    }

    public function test_validate_pm_invalid()
    {
        $project = new Project($this->valid_project_data);
        $project->username = 'ProjectTest_FakeUser';
        $errors = $project->validate();
        $this->assertStringContainsString("must be an existing user", $errors[0]);
    }

    public function test_validate_pm_not_a_pm()
    {
        $project = new Project($this->valid_project_data);
        $project->username = $this->TEST_USERNAME;
        $errors = $project->validate();
        $this->assertStringContainsString("not a PM", $errors[0]);
    }

    public function test_validate_language_missing()
    {
        $project = new Project($this->valid_project_data);
        $project->language = '';
        $errors = $project->validate();
        $this->assertStringContainsString("required", $errors[0]);
    }

    public function test_validate_language_invalid()
    {
        $project = new Project($this->valid_project_data);
        $project->language = 'Fake Language';
        $errors = $project->validate();
        $this->assertStringContainsString("not a valid language", $errors[0]);
    }

    public function test_validate_language_duplicate()
    {
        $project = new Project($this->valid_project_data);
        $project->languages = ['English', 'English'];
        $errors = $project->validate();
        $this->assertStringContainsString("Languages must be unique.", $errors[0]);
    }

    public function test_validate_genre_missing()
    {
        $project = new Project($this->valid_project_data);
        $project->genre = '';
        $errors = $project->validate();
        $this->assertStringContainsString("required", $errors[0]);
    }

    public function test_validate_genre_invalid()
    {
        $project = new Project($this->valid_project_data);
        $project->genre = 'Fake Genre';
        $errors = $project->validate();
        $this->assertStringContainsString("not a valid genre", $errors[0]);
    }

    public function test_validate_difficulty_missing()
    {
        $project = new Project($this->valid_project_data);
        $project->difficulty = '';
        $errors = $project->validate();
        $this->assertStringContainsString("required", $errors[0]);
    }

    public function test_validate_difficulty_invalid()
    {
        $project = new Project($this->valid_project_data);
        $project->difficulty = 'insanely_hard';
        $errors = $project->validate();
        $this->assertStringContainsString("not a valid difficulty", $errors[0]);
    }

    public function test_validate_otherday_positive_path()
    {
        $project = new Project($this->valid_project_data);
        $project->special_code = "Otherday 0101";
        $errors = $project->validate();
        $this->assertEquals([], $errors);
    }

    public function test_validate_otherday_invalid()
    {
        $project = new Project($this->valid_project_data);
        $project->special_code = "Otherday 9901";
        $errors = $project->validate();
        $this->assertStringContainsString("Invalid date supplied", $errors[0]);
    }

    public function test_validate_postdnum_positive_path()
    {
        $project = new Project($this->valid_project_data);
        $project->posted_num = 123;
        $errors = $project->validate();
        $this->assertEquals([], $errors);
    }

    public function test_validate_postedenum_invalid()
    {
        $project = new Project($this->valid_project_data);
        $project->postednum = "invalid";
        $errors = $project->validate();
        $this->assertStringContainsString("not of the correct format", $errors[0]);
    }

    public function test_validate_custom_chars_positive_path()
    {
        $project = new Project($this->valid_project_data);
        $project->custom_chars = '1';
        $errors = $project->validate();
        $this->assertEquals([], $errors);
    }

    public function test_validate_custom_chars_duplicates()
    {
        $project = new Project($this->valid_project_data);
        $project->custom_chars = '11';
        $errors = $project->validate();
        $this->assertStringContainsString("must be unique", $errors[0]);
    }

    public function test_validate_custom_chars_too_many()
    {
        $project = new Project($this->valid_project_data);
        $project->custom_chars = 'abcdefghijklmnopqrstuvwxyABCDEFGHJKLMNOPQRSTUVWXYZ';
        $errors = $project->validate();
        $this->assertStringContainsString("maximum of 32", $errors[0]);
    }

    public function test_validate_custom_chars_invalid()
    {
        $project = new Project($this->valid_project_data);
        $project->custom_chars = '…';
        $errors = $project->validate();
        $this->assertStringContainsString("are not allowed", $errors[0]);
    }

    //------------------------------------------------------------------------
    // projectID validation

    public function test_validate_projectID_positive_path()
    {
        validate_projectID($this->valid_projectID);

        // PHPUnit labels tests without at least one assert as risky
        $this->assertTrue(true);
    }

    public function test_validate_projectID_negative_path()
    {
        $this->expectException(InvalidProjectIDException::class);
        validate_projectID("1234");
    }

    public function test_get_projectID_param_positive_path()
    {
        $params = [
            "projectid" => $this->valid_projectID,
        ];
        $projectid = get_projectID_param($params, "projectid");
        $this->assertEquals($this->valid_projectID, $projectid);
    }

    public function test_get_projectID_param_null_positive_path()
    {
        $params = [];
        $projectid = get_projectID_param($params, "projectid", true);
        $this->assertEquals(null, $projectid);
    }

    public function test_get_projectID_param_null_negative_path()
    {
        $this->expectException(InvalidProjectIDException::class);
        $params = [];
        $projectid = get_projectID_param($params, "projectid");
    }

    //------------------------------------------------------------------------
    // page image validation

    public function test_validate_page_image_positive_path()
    {
        validate_page_image($this->valid_page_image);

        // PHPUnit labels tests without at least one assert as risky
        $this->assertTrue(true);
    }

    public function test_validate_page_image_negative_path()
    {
        $this->expectException(InvalidPageException::class);
        validate_page_image("1234");
    }

    public function test_get_page_image_param_positive_path()
    {
        $params = [
            "image" => $this->valid_page_image,
        ];
        $image = get_page_image_param($params, "image");
        $this->assertEquals($this->valid_page_image, $image);
    }

    public function test_get_page_image_param_null_positive_path()
    {
        $params = [];
        $image = get_page_image_param($params, "image", true);
        $this->assertEquals(null, $image);
    }

    public function test_get_page_image_param_null_negative_path()
    {
        $this->expectException(InvalidPageException::class);
        $params = [];
        $image = get_page_image_param($params, "image");
    }

    // tests for can_be_proofed_by_current_user()

    public function test_can_be_proofed_not_in_round()
    {
        global $pguser;

        $this->expectExceptionCode(110);
        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_project_with_page();
        $project->can_be_proofed_by_current_user();
    }

    public function test_can_be_proofed_not_available()
    {
        global $pguser;

        $this->expectExceptionCode(112);
        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_project_with_page();
        project_transition($project->projectid, PROJ_P1_UNAVAILABLE, $this->TEST_USERNAME_PM);
        $project = new Project($project->projectid);
        $project->can_be_proofed_by_current_user();
    }

    public function test_can_be_proofed_available()
    {
        global $pguser;

        $pguser = $this->TEST_USERNAME;
        $project = $this->_create_project_with_page();
        $this->make_project_available($project->projectid);
        $project = new Project($project->projectid);
        $project->can_be_proofed_by_current_user();
        $this->assertTrue(true);
    }

    public function test_can_be_proofed_user_not_logged_in()
    {
        global $pguser;
        $pguser = null;

        $this->expectExceptionCode(301);
        $project = $this->_create_project_with_page();
        $this->make_project_available($project->projectid);
        $project = new Project($project->projectid);
        $project->can_be_proofed_by_current_user();
    }

    public function test_can_be_proofed_user_not_qualified()
    {
        global $pguser;
        $pguser = $this->TEST_USERNAME;
        $userSettings = & Settings::get_Settings($pguser);
        $userSettings->set_value("P2.access", "no");

        $this->expectExceptionCode(302);
        $project = $this->_create_project_with_page();
        $this->make_project_available($project->projectid);
        $this->advance_to_round2($project->projectid);
        $project = new Project($project->projectid);
        $project->can_be_proofed_by_current_user();
    }
}
