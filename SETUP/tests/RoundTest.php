<?php

class ActivityTest extends PHPUnit\Framework\TestCase
{
    public function test_stages_class()
    {
        $this->assertEquals("F2", Stages::get_by_id('F2')->id);
    }

    public function test_stages_functions()
    {
        $this->assertEquals("F2", get_Stage_for_id('F2')->id);
    }


    public function test_rounds_class()
    {
        $this->assertEquals("P1", Rounds::get_by_number(1)->id);
        $this->assertEquals("P2", Rounds::get_by_page_state("P2.page_temp")->id);
        $this->assertEquals("P3", Rounds::get_by_id("P3")->id);
        $this->assertEquals("P2.page_saved", Rounds::get_page_states()[8]);
        $this->assertEquals("P2", Rounds::get_by_project_state("P2.proj_waiting")->id);
    }

    public function test_rounds_functions()
    {
        $this->assertEquals("P1", get_Round_for_round_number(1)->id);
        $this->assertEquals("P2", get_Round_for_page_state("P2.page_temp")->id);
        $this->assertEquals("P3", get_Round_for_round_id("P3")->id);
        $this->assertEquals("F1", get_Round_for_text_column_name("round4_text")->id);
        $this->assertEquals("F2", get_Round_for_text_column_name("round5_text")->id);
        $this->assertEquals("P2", get_Round_for_project_state("P2.proj_waiting")->id);
    }

    public function test_pools_class()
    {
        $this->assertEquals("PP", Pools::get_by_id("PP")->id);
        $this->assertEquals("PPV", Pools::get_by_id("PPV")->id);
        $this->assertEquals(null, Pools::get_by_id("P2"));
        $this->assertEquals("PP", Pools::get_by_state(PROJ_POST_FIRST_AVAILABLE)->id);
        $this->assertEquals("PP", Pools::get_by_state(PROJ_POST_FIRST_CHECKED_OUT)->id);
        $this->assertEquals("PPV", Pools::get_by_state(PROJ_POST_SECOND_CHECKED_OUT)->id);
    }

    public function test_pools_functions()
    {
        $this->assertEquals("PP", get_Pool_for_id("PP")->id);
        $this->assertEquals("PPV", get_Pool_for_id("PPV")->id);
        $this->assertEquals(null, get_Pool_for_id("P2"));
        $this->assertEquals("PP", get_Pool_for_state(PROJ_POST_FIRST_AVAILABLE)->id);
        $this->assertEquals("PP", get_Pool_for_state(PROJ_POST_FIRST_CHECKED_OUT)->id);
        $this->assertEquals("PPV", get_Pool_for_state(PROJ_POST_SECOND_CHECKED_OUT)->id);
    }

    public function test_access_criteria_global()
    {
        global $ACCESS_CRITERIA;
        $this->assertEquals("'P2' pages completed", $ACCESS_CRITERIA["P2"]);
    }

    //------------------------------------------------------------------------
    // get_round_param() tests

    public function test_get_round_param()
    {
        $GET = ["round" => "P1"];
        $round = get_round_param($GET, "round");
        $this->assertEquals("P1", $round->id);
    }

    public function test_get_round_param_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is not a valid round ID");
        $GET = ["round" => "X4"];
        get_round_param($GET, "round");
    }

    public function test_get_round_param_default()
    {
        $round = get_round_param([], "round", Rounds::get_by_id("P1"));
        $this->assertEquals("P1", $round->id);
    }

    public function test_get_round_param_default_null()
    {
        $round = get_round_param([], "round", null, true);
        $this->assertEquals(null, $round);
    }

    public function test_get_round_param_no_default()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is required");
        get_round_param([], "round", null);
    }

    //------------------------------------------------------------------------
    // test project state functions

    public function test_project_state_functions()
    {
        global $PROJECT_STATES_IN_ORDER, $waiting_projects_forum_idx, $projects_forum_idx, $pp_projects_forum_idx;

        $this->assertEquals("P3.proj_bad", $PROJECT_STATES_IN_ORDER[11]);
        $this->assertEquals("project_delete", $PROJECT_STATES_IN_ORDER[34]);
        $this->assertEquals("(state='proj_submit_pgposted')", SQL_CONDITION_GOLD);
        $this->assertEquals("(state='P1.proj_avail' OR state='P2.proj_avail' OR state='P3.proj_avail' OR state='F1.proj_avail' OR state='F2.proj_avail')", SQL_CONDITION_BRONZE);

        $this->assertEquals("P1: Waiting", get_medium_label_for_project_state(PROJ_P1_WAITING_FOR_RELEASE));
        $this->assertEquals("Proofreading Round 1: Waiting for Release", project_states_text(PROJ_P1_WAITING_FOR_RELEASE));
        $this->assertEquals('PAGE_EDITING', get_phase_containing_project_state(PROJ_P1_WAITING_FOR_RELEASE));

        $this->assertEquals("PP: Available", get_medium_label_for_project_state(PROJ_POST_FIRST_AVAILABLE));
        $this->assertEquals("PP: Checked out", get_medium_label_for_project_state(PROJ_POST_FIRST_CHECKED_OUT));
        $this->assertEquals("Post-Processing: Available", project_states_text(PROJ_POST_FIRST_AVAILABLE));
        $this->assertEquals($waiting_projects_forum_idx, get_forum_id_for_project_state(PROJ_P1_WAITING_FOR_RELEASE));
        $this->assertEquals($projects_forum_idx, get_forum_id_for_project_state(PROJ_P2_WAITING_FOR_RELEASE));
        $this->assertEquals($pp_projects_forum_idx, get_forum_id_for_project_state(PROJ_POST_FIRST_AVAILABLE));

        $this->assertEquals("PPV: Available", get_medium_label_for_project_state(PROJ_POST_SECOND_AVAILABLE));
        $this->assertEquals("Post-Processing Verification: Available", project_states_text(PROJ_POST_SECOND_AVAILABLE));
    }

    public function test_graph_data_functions()
    {
        $this->assertEquals("state IN ('proj_post_first_unavailable','proj_post_first_available','proj_post_first_checked_out','proj_post_second_available','proj_post_second_checked_out','proj_post_complete')", _get_project_state_selector('PP'));
        $this->assertEquals("state IN ('project_complete')", _get_project_state_selector('COMPLETE'));

    }
}
