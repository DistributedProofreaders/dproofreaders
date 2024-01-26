<?php

class RoundTest extends ProjectUtils
{
    //------------------------------------------------------------------------

    public function test_activities()
    {
        $this->assertEquals("P1", get_Round_for_round_number(1)->id);
        $this->assertEquals("P2", get_Round_for_page_state("P2.page_temp")->id);
        $this->assertEquals("P3", get_Round_for_round_id("P3")->id);
        $this->assertEquals("F1", get_Round_for_text_column_name("round4_text")->id);
        $this->assertEquals("F2", get_Round_for_text_column_name("round5_text")->id);
        $this->assertEquals("F2", get_Stage_for_id('F2')->id);
        $p1 = get_Round_for_round_id("P1");
        $this->assertEquals('P1.proj_unavail', $p1->project_unavailable_state);
        $this->assertEquals('Proofreading Prodigy', $p1->get_honorific_for_page_tally(1200));
        $this->assertEquals('Novice', $p1->get_honorific_for_page_tally(-10));
        $this->assertEquals("PP", get_Pool_for_id("PP")->id);
        $this->assertEquals("PPV", get_Pool_for_id("PPV")->id);
        $this->assertEquals(null, get_Pool_for_id("P2"));
        $this->assertEquals("P2.page_saved", Rounds::get_page_states()[8]);

        global $ACCESS_CRITERIA;
        $this->assertEquals("'P2' pages completed", $ACCESS_CRITERIA["P2"]);
    }

    public function test_project_states()
    {
        global $projects_forum_idx, $waiting_projects_forum_idx, $PROJECT_STATES_IN_ORDER;

        $this->assertEquals("P2", get_Round_for_project_state("P2.proj_waiting")->id);
        $this->assertTrue(PROJ_P1_UNAVAILABLE === "P1.proj_unavail");
        $this->assertEquals("PP", get_Pool_for_state(PROJ_POST_FIRST_AVAILABLE)->id);
        $this->assertEquals("PP", get_Pool_for_state(PROJ_POST_FIRST_CHECKED_OUT)->id);
        $this->assertEquals("PPV", get_Pool_for_state(PROJ_POST_SECOND_CHECKED_OUT)->id);
        $this->assertEquals("F1.proj_bad", ProjectStates::get_states_in_order()[16]);
        $this->assertEquals("P2: Waiting", get_medium_label_for_project_state("P2.proj_waiting"));
        $this->assertEquals("PP: Available", get_medium_label_for_project_state("proj_post_first_available"));
        $this->assertEquals("Post-Processing: Available", project_states_text("proj_post_first_available"));
        $this->assertEquals("Proofreading Round 2: Waiting for Release", project_states_text("P2.proj_waiting"));
        // $this->assertEquals($projects_forum_idx, get_forum_id_for_project_state("P2.proj_unavail"));
        // $this->assertEquals($waiting_projects_forum_idx, get_forum_id_for_project_state("P1.proj_unavail"));
        $this->assertEquals('PAGE_EDITING', ProjectStates::get_phase_containing_project_state("P1.proj_bad"));
        $this->assertEquals("(state='proj_submit_pgposted')", SQL_CONDITION_GOLD);
    }
}
