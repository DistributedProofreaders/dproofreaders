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
        // username and image_source are set in setUp() after creation
    ];

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
        $this->expectException(ValueError::class);
        $project->validate(true);
    }

    public function test_validate_nameofwork_missing()
    {
        $project = new Project($this->valid_project_data);
        $project->nameofwork = '';
        $errors = $project->validate();
        $this->assertStringContainsString("required", $errors[0]);
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
}
