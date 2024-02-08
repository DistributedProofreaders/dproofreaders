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
}
