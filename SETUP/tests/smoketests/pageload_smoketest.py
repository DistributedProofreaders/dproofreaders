#!/usr/bin/env python3

import argparse
import fnmatch
import json
import os
import re
import signal
import sys
import time

from http.cookiejar import CookieJar, DefaultCookiePolicy
from subprocess import Popen, DEVNULL, run, STDOUT, PIPE
from urllib.error import URLError
from urllib.parse import urlencode, urlparse
from urllib.request import build_opener, HTTPCookieProcessor, HTTPErrorProcessor, Request

from typing import List, Optional, Tuple

SERVER_LOG = "server.log"

WEB_TESTS = [
    {'name': 'nologin-addproofer', 'path': 'accounts/addproofer.php'},
    {'name': 'nologin-credits', 'path': 'credits.php'},
    {'name': 'nologin-index', 'path': 'index.php'},
    {'name': 'nologin-list-etexts', 'path': 'list_etexts.php'},
    {'name': 'nologin-list-etexts-genre', 'path': 'list_etexts.php?x=g'},
    {'name': 'nologin-list-etexts-search', 'path': 'list_etexts.php?x=s'},
    {'name': 'nologin-list-etexts-browse', 'path': 'list_etexts.php?x=b'},

    {'name': 'base-activity-hub', 'path': 'activity_hub.php'},
    {'name': 'base-pastnews', 'path': 'pastnews.php'},
    {'name': 'base-project', 'path': 'project.php?id=projectID5e23a810ef693'},
    {'name': 'base-project-1', 'path': 'project.php?id=projectID5e23a810ef693&detail_level=1'},
    {'name': 'base-project-2', 'path': 'project.php?id=projectID5e23a810ef693&detail_level=2'},
    {'name': 'base-project-3', 'path': 'project.php?id=projectID5e23a810ef693&detail_level=3'},
    {'name': 'base-project-4', 'path': 'project.php?id=projectID5e23a810ef693&detail_level=4'},
    {'name': 'base-tasks', 'path': 'tasks.php'},
    {'name': 'base-tasks-1', 'path': 'tasks.php?task_id=2277&action=show'},
    {'name': 'base-userprefs', 'path': 'userprefs.php'},
    {'name': 'base-login-failure', 'path': 'accounts/login_failure.php', 'expect_status': 302},
    {'name': 'base-require-login', 'path': 'accounts/require_login.php'},

    {'name': 'api-projects', 'path': 'api/index.php?url=v1/projects'},
    {'name': 'api-project', 'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693'},
    {'name': 'api-project-wordlist-good', 'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/wordlists/good'},
    {'name': 'api-project-holdstates', 'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/holdstates'},
    {'name': 'api-project-pages', 'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/pages'},
    {'name': 'api-project-pagedetails', 'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/pagedetails'},
    {'name': 'api-project-pagerounds', 'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/pages/001.png/pagerounds/P1'},
    {'name': 'api-project-transitions', 'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/transitions'},
    {'name': 'api-difficulties', 'path': 'api/index.php?url=v1/projects/difficulties'},
    {'name': 'api-genres', 'path': 'api/index.php?url=v1/projects/genres'},
    {'name': 'api-states', 'path': 'api/index.php?url=v1/projects/states'},
    {'name': 'api-pagerounds', 'path': 'api/index.php?url=v1/projects/pagerounds'},
    {'name': 'api-charsuites', 'path': 'api/index.php?url=v1/projects/charsuites'},
    {'name': 'api-specialdays', 'path': 'api/index.php?url=v1/projects/specialdays'},
    {'name': 'api-imagesources', 'path': 'api/index.php?url=v1/projects/imagesources'},
    {'name': 'api-holdstates', 'path': 'api/index.php?url=v1/projects/holdstates'},

    {
        'name': 'api-validatetext',
        'method': 'PUT',
        'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/validatetext',
        'json': { 'text': 'Un soupçon de café ♨︎' },
    },
    {
        'name': 'api-wordcheck',
        'method': 'PUT',
        'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/wordcheck',
        'json': {
            'text': 'Sidenote: A café in the arid desert',
            'accepted_words': ['café'],
        },
    },

    {'name': 'api-queues', 'path': 'api/index.php?url=v1/queues'},
    {'name': 'api-queue', 'path': 'api/index.php?url=v1/queues/500'},
    {'name': 'api-queue-stats', 'path': 'api/index.php?url=v1/queues/500/stats'},
    {'name': 'api-queue-projects', 'path': 'api/index.php?url=v1/queues/500/projects'},

    {'name': 'api-stats-site', 'path': 'api/index.php?url=v1/stats/site'},
    {'name': 'api-stats-project-stages', 'path': 'api/index.php?url=v1/stats/site/projects/stages'},
    {'name': 'api-stats-project-states', 'path': 'api/index.php?url=v1/stats/site/projects/states'},
    {'name': 'api-stats-rounds', 'path': 'api/index.php?url=v1/stats/site/rounds'},
    {'name': 'api-stats-round', 'path': 'api/index.php?url=v1/stats/site/rounds/P3'},

    {
        'name': 'api-invalid-delete-projects',
        'method': 'DELETE',
        'path': 'api/index.php?url=v1/projects',
        'expect_status': 405
    },
    {
        'name': 'api-invalid-bad-endpoint',
        'path': 'api/index.php?url=v1/projectz/projectID5e23a810ef693/wordcheck',
        'expect_status': 404
    },
    {
        'name': 'api-invalid-wordcheck-subpath',
        'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/wordcheck/ai',
        'expect_status': 404
    },
    {
        'name': 'api-invalid-stats',
        'path': 'api/index.php?url=v1/stats',
        'expect_status': 404
    },
    {
        'name': 'api-invalid-checkout-state',
        'method': 'PUT',
        'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/checkout&state=wibble',
        'expect_status': 400
    },
    {
        'name': 'api-invalid-page-state',
        'method': 'PUT',
        'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/pages/042.png&state=F1.proj_avail&pagestate=wibble',
        'expect_status': 400
    },
    {
        'name': 'api-invalid-dictionaries-params',
        'path': 'api/index.php?url=v1/dictionaries&param1=invalid&param2',
        'expect_status': 400
    },
    {
        'name': 'api-invalid-duplicate-field',
        'path': 'api/index.php?url=v1/projects&per_page=1&page=1&state=P3.proj_avail&field[]=title&field[]=title',
        'expect_status': 400
    },

    {'name': 'faq-doc-copy', 'path': 'faq/doc-copy.php'},
    {'name': 'faq-font-sample', 'path': 'faq/font_sample.php'},
    {'name': 'faq-privacy', 'path': 'faq/privacy.php'},
    {'name': 'faq-prooffacehelp', 'path': 'faq/prooffacehelp.php'},
    {'name': 'faq-progress-snapshot-legend', 'path': 'faq/site_progress_snapshot_legend.php'},
    {'name': 'faq-translate', 'path': 'faq/translate.php'},

    {'name': 'misc-feeds-backend', 'path': 'feeds/backend.php'},
    {'name': 'misc-debug-ui-language', 'path': 'locale/debug_ui_language.php'},
    {'name': 'misc-translators-index', 'path': 'locale/translators/index.php'},

    {'name': 'quiz-start', 'path': 'quiz/start.php'},
    {'name': 'quiz-hints', 'path': 'quiz/generic/hints.php?quiz_page_id=p_basicx_2&error=arid&number=1'},
    {'name': 'quiz-main', 'path': 'quiz/generic/main.php?quiz_page_id=p_basic_1'},
    {'name': 'quiz-orig', 'path': 'quiz/generic/orig.php?quiz_page_id=p_basic_2'},
    {'name': 'quiz-proof', 'path': 'quiz/generic/proof.php?quiz_page_id=p_basic_3'},
    {'name': 'quiz-returnfeed', 'path': 'quiz/generic/returnfeed.php?quiz_page_id=p_thorn'},
    {'name': 'quiz-right', 'path': 'quiz/generic/right.php?quiz_page_id=p_fraktur'},
    {'name': 'quiz-wizard-default-messages', 'path': 'quiz/generic/wizard/default_messages.php'},
    {'name': 'quiz-wizard-start', 'path': 'quiz/generic/wizard/start.php'},
    {'name': 'quiz-wizard-new-quiz', 'path': 'quiz/generic/wizard/new_quiz.php'},
    # TODO: Needs SESSION data
    #'quiz/generic/wizard/general.php',
    #'quiz/generic/wizard/checks.php',
    #'quiz/generic/wizard/messages.php',
    #'quiz/generic/wizard/output.php',
    #'quiz/generic/wizard/output_quiz.php',
    #'quiz/generic/wizard/quiz_pages.php',

    {'name': 'quiz-aeoe-1', 'path': 'quiz/tuts/tut_p_aeoe_1.php'},
    {'name': 'quiz-aeoe-2', 'path': 'quiz/tuts/tut_p_aeoe_2.php'},
    {'name': 'quiz-basic-1', 'path': 'quiz/tuts/tut_p_basic_1.php'},
    {'name': 'quiz-basic-2', 'path': 'quiz/tuts/tut_p_basic_2.php'},
    {'name': 'quiz-basic-3', 'path': 'quiz/tuts/tut_p_basic_3.php'},
    {'name': 'quiz-basic-4', 'path': 'quiz/tuts/tut_p_basic_4.php'},
    {'name': 'quiz-basic-5', 'path': 'quiz/tuts/tut_p_basic_5.php'},
    {'name': 'quiz-fraktur', 'path': 'quiz/tuts/tut_p_fraktur.php'},
    {'name': 'quiz-mod1-1', 'path': 'quiz/tuts/tut_p_mod1_1.php'},
    {'name': 'quiz-mod1-2', 'path': 'quiz/tuts/tut_p_mod1_2.php'},
    {'name': 'quiz-mod1-3', 'path': 'quiz/tuts/tut_p_mod1_3.php'},
    {'name': 'quiz-mod1-4', 'path': 'quiz/tuts/tut_p_mod1_4.php'},
    {'name': 'quiz-mod1-5', 'path': 'quiz/tuts/tut_p_mod1_5.php'},
    {'name': 'quiz-mod2-1', 'path': 'quiz/tuts/tut_p_mod2_1.php'},
    {'name': 'quiz-mod2-2', 'path': 'quiz/tuts/tut_p_mod2_2.php'},
    {'name': 'quiz-mod2-3', 'path': 'quiz/tuts/tut_p_mod2_3.php'},
    {'name': 'quiz-mod2-4', 'path': 'quiz/tuts/tut_p_mod2_4.php'},
    {'name': 'quiz-mod2-5', 'path': 'quiz/tuts/tut_p_mod2_5.php'},
    {'name': 'quiz-old-1', 'path': 'quiz/tuts/tut_p_old_1.php'},
    {'name': 'quiz-old-2', 'path': 'quiz/tuts/tut_p_old_2.php'},
    {'name': 'quiz-old-3', 'path': 'quiz/tuts/tut_p_old_3.php'},
    {'name': 'quiz-thorn', 'path': 'quiz/tuts/tut_p_thorn.php'},

    # NB performs an action
    {'name': 'teams-jointeam', 'path': 'stats/members/jointeam.php?tid=44', 'expect_status': [200, 302]},
    # NB performs an action
    {'name': 'teams-quitteam', 'path': 'stats/members/quitteam.php?tid=44', 'expect_status': [200, 302]},
    # Adds a forum post!
    {'name': 'teams-team-topic', 'path': 'stats/teams/team_topic.php?team=44', 'expect_status': 302},
    {'name': 'teams-mbr-list', 'path': 'stats/members/mbr_list.php?tid=44'},
    {'name': 'teams-mbr-xml', 'path': 'stats/members/mbr_xml.php?username=teststeel'},
    {'name': 'teams-mdetail', 'path': 'stats/members/mdetail.php?id=1'},
    {'name': 'teams-mdetail-nouser', 'path': 'stats/members/mdetail.php?id=999999'},
    {'name': 'teams-mdetail-all', 'path': 'stats/members/mdetail.php?id=1&tally_name=F1&range=all'},
    {'name': 'teams-new-team', 'path': 'stats/teams/new_team.php'},
    {'name': 'teams-tdetail', 'path': 'stats/teams/tdetail.php?tid=44'},
    {'name': 'teams-tdetail-all', 'path': 'stats/teams/tdetail.php?tid=44&tally_name=P3&range=all'},
    {'name': 'teams-teams-xml', 'path': 'stats/teams/teams_xml.php?tid=44'},
    {'name': 'teams-tedit', 'path': 'stats/teams/tedit.php?tid=44'},
    {'name': 'teams-tlist', 'path': 'stats/teams/tlist.php'},

    {'name': 'stats-pp-unknown', 'path': 'stats/PP_unknown.php'},
    {'name': 'stats-equilibria', 'path': 'stats/equilibria.php'},
    {'name': 'stats-misc-stats1', 'path': 'stats/misc_stats1.php?tally_name=F1&start=2024-01&end=2024-02'},
    {'name': 'stats-misc-user-graphs', 'path': 'stats/misc_user_graphs.php'},
    {'name': 'stats-pages-in-states', 'path': 'stats/pages_in_states.php'},
    {'name': 'stats-pages-proofed-graphs', 'path': 'stats/pages_proofed_graphs.php?tally_name=P3'}, # Slow to evaluate!
    {'name': 'stats-percent-users-who-proof', 'path': 'stats/percent_users_who_proof.php'},
    {'name': 'stats-pm-stats', 'path': 'stats/pm_stats.php'},
    {'name': 'stats-pp-stage-goal', 'path': 'stats/pp_stage_goal.php'},
    {'name': 'stats-pp-stats', 'path': 'stats/pp_stats.php'},
    {'name': 'stats-ppv-stats', 'path': 'stats/ppv_stats.php'},
    {'name': 'stats-projects-xed-graphs', 'path': 'stats/projects_Xed_graphs.php?which=posted'},
    {'name': 'stats-proof-stats', 'path': 'stats/proof_stats.php?tally_name=F2'},
    {'name': 'stats-release-queue', 'path': 'stats/release_queue.php'},
    {'name': 'stats-requested-books', 'path': 'stats/requested_books.php'},
    {'name': 'stats-round-backlog', 'path': 'stats/round_backlog.php'},
    {'name': 'stats-round-backlog-days', 'path': 'stats/round_backlog_days.php'},
    {'name': 'stats-index', 'path': 'stats/index.php'},
    {'name': 'stats-user-logon-graphs', 'path': 'stats/user_logon_graphs.php'},

    {'name': 'styles-design-philosophy', 'path': 'styles/design_philosophy.php'},
    {'name': 'styles-style-demo', 'path': 'styles/style_demo.php'},

    {
        'name': 'tools-change-sr-commitment',
        'method': 'POST',
        'path': 'tools/change_sr_commitment.php',
        'data': {'projectid': 'projectID5e23a810ef693','action': 'commit'}
    },
    {
        'name': 'tools-changestate',
        'method': 'POST',
        'path': 'tools/changestate.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'curr_state': 'P3.proj_avail',
            'next_state': 'P3.proj_unavail',
            'confirmed': 'yes',
            'return_uri': 'http://127.0.0.1:12345/',
        },
    },
    {'name': 'tools-charsuites', 'path': 'tools/charsuites.php'},
    {'name': 'tools-charsuites-project', 'path': 'tools/charsuites.php?projectid=projectID5e23a810ef693'},
    {
        'name': 'tools-download-images',
        'path': 'tools/download_images.php?projectid=projectID5e23a810ef693',
        'expect_status': 302,
    },
    {'name': 'tools-extend-sr', 'path': 'tools/extend_sr.php?project=projectID5e23a810ef693&days=10'},
    {
        'name': 'tools-modify-access-grant',
        'method': 'POST',
        'path': 'tools/modify_access.php',
        'data': {
            'subject_username': 'admin',
            'F2|grant': 'on',
        },
    },
    {
        'name': 'tools-modify-access-revoke',
        'method': 'POST',
        'path': 'tools/modify_access.php',
        'data': {
            'subject_username': 'admin',
            'F2|revoke': 'on',
        },
    },
    {'name': 'tools-page-browser', 'path': 'tools/page_browser.php?project=projectID5e23a810ef693&imagefile=001.png'},
    {'name': 'tools-pending-access-requests', 'path': 'tools/pending_access_requests.php'},
    {'name': 'tools-pool', 'path': 'tools/pool.php?pool_id=PP'},
    {
        'name': 'tools-remove-project-hold',
        'method': 'POST',
        'path': 'tools/remove_project_hold.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'curr_state': 'P3.proj_avail',
            'return_uri': '',
        },
        'expect_status': 302,
    },
    {'name': 'tools-request-access', 'path': 'tools/request_access.php?stage_id=F2'},
    {'name': 'tools-search', 'path': 'tools/search.php?show=search&title=A'},
    {
        'name': 'tools-set-project-event-subs',
        'method': 'POST',
        'path': 'tools/set_project_event_subs.php',
        'data': {'projectid': 'projectID5e23a810ef693', 'posted': 'on'},
    },
    {
        'name': 'tools-set-project-holds',
        'method': 'POST',
        'path': 'tools/set_project_holds.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'P3_proj_avail': 'on',
            'return_uri': ''
        },
    },
    {
        'name': 'tools-setlangcookie',
        'method': 'POST',
        'path': 'tools/setlangcookie.php',
        'data': {'lang': 'en_US'},
        'expect_status': 302,
    },

    {
        'name': 'tools-post-prfs-postcomments',
        'method': 'POST',
        'path': 'tools/post_proofers/postcomments.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'comments': 'It was a dark and stormy night',
        },
    },
    {'name': 'tools-pprfs-ppv-report', 'path': 'tools/post_proofers/ppv_report.php?project=projectID5e23a810ef693'},
    {'name': 'tools-pprfs-smooth-reading', 'path': 'tools/post_proofers/smooth_reading.php'},

    # TODO add project in P1.unavail
    {'name': 'tools-pm-add-files', 'path': 'tools/project_manager/add_files.php?project=projectID5e23a810ef693'},
    {'name': 'tools-pm-automodify', 'path': 'tools/project_manager/automodify.php'},
    {'name': 'tools-pm-bad-bytes-explainer', 'path': 'tools/project_manager/bad_bytes_explainer.php'},
    {'name': 'tools-pm-clearance-check', 'path': 'tools/project_manager/clearance_check.php'},
    {'name': 'tools-pm-diff', 'path': 'tools/project_manager/diff.php?project=projectID5e23a810ef693&L_round=P1&R_round=P2&image=001.png'},
    # (deliberately) not clicking 'Confirm'...
    {'name': 'tools-pm-edit-pages', 'path': 'tools/project_manager/edit_pages.php?projectid=projectID5e23a810ef693&operation=clear&selected_pages[001.png]=on'},
    # TODO project dir doesn't exist
    {'name': 'tools-pm-edit-word-lists', 'path': 'tools/project_manager/edit_project_word_lists.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-editproject-edit', 'path': 'tools/project_manager/editproject.php?project=projectID5e23a810ef693&action=edit'},
    # Test some more codepaths, and missing param cases
    {'name': 'tools-pm-editproject-edit-noproject', 'path': 'tools/project_manager/editproject.php?action=edit'},
    {'name': 'tools-pm-editproject-clone', 'path': 'tools/project_manager/editproject.php?action=clone'},
    {
        'name': 'tools-pm-editproject-save',
        'method': 'POST',
        'path': 'tools/project_manager/editproject.php?project=projectID5e23a810ef693',
        'data': {'save': 1},
    },
    {
        'name': 'tools-pm-editproject-preview',
        'method': 'POST',
        'path': 'tools/project_manager/editproject.php?project=projectID5e23a810ef693',
        'data': {'preview': 1},
    },
    {'name': 'tools-pm-catalog-search-form', 'path': 'tools/project_manager/external_catalog_search.php?action=show_query_form'},
    # TODO yaz
    #{'path': 'tools/project_manager/external_catalog_search.php?action=do_search_and_show_hits'},
    {'name': 'tools-pm-generate-post-files', 'path': 'tools/project_manager/generate_post_files.php?projectid=projectID5e23a810ef693&round_id=P3&which_text=EQ'},
    {'name': 'tools-pm-handle-bad-page', 'path': 'tools/project_manager/handle_bad_page.php?projectid=projectID5e23a810ef693&image=001.png'},
    {'name': 'tools-pm-manage-image-sources', 'path': 'tools/project_manager/manage_image_sources.php?action=show_sources'},
    {'name': 'tools-pm-marc-inspector', 'path': 'tools/project_manager/marc_inspector.php?rec=H4sIAAAAAAAAA23QwQrCMAwG4FcJPSmI_Enbrc0uvko2PQgq4rzJ3t2uQ2GwXpo0_b9CTVv9XBWdqSzFqAx1O3-4Xex8ee1dd1UupxLUATnkh93JRABJmYxCBFw3zZdM-We0lQB4X2ey8uU_LNvp_0LJeJ9ahtSM3_Li4oVNL649btRxTgmRBTmKyDHXdNyS0yI3m3Jay6H8UHE9p5Fzw1TW-CTrh1L0cweAQIO9iYo6fQG-RwV6ZgEAAA'},
    {'name': 'tools-pm-page-compare', 'path': 'tools/project_manager/page_compare.php?project=projectID5e23a810ef693&L_round=P1&R_round=P2'},
    {'name': 'tools-pm-page-detail', 'path': 'tools/project_manager/page_detail.php?project=projectID5e23a810ef693'},
    {'name': 'tools-pm-project-quick-check', 'path': 'tools/project_manager/project_quick_check.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-projectmgr-avail', 'path': 'tools/project_manager/projectmgr.php?show=user_avail'},
    {'name': 'tools-pm-projectmgr-active', 'path': 'tools/project_manager/projectmgr.php?show=user_active'},
    {'name': 'tools-pm-projectmgr-all', 'path': 'tools/project_manager/projectmgr.php?show=user_all'},
    {'name': 'tools-pm-projectmgr-search-form', 'path': 'tools/project_manager/projectmgr.php?show=search_form'},
    {'name': 'tools-pm-remote-file-manager', 'path': 'tools/project_manager/remote_file_manager.php'},
    {'name': 'tools-pm-adhoc-word-details', 'path': 'tools/project_manager/show_adhoc_word_details.php?projectid=projectID5e23a810ef693'},
    {
        'name': 'tools-pm-adhoc-word-details-query',
        'method': 'POST',
        'path': 'tools/project_manager/show_adhoc_word_details.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'queryWordText': 'the\nwho',
            'freqCutoff': 1
        },
    },
    {'name': 'tools-pm-all-good-word-suggestions', 'path': 'tools/project_manager/show_all_good_word_suggestions.php'},
    # TODO add word lists to project to exercise this better
    {'name': 'tools-pm-current-flagged-words', 'path': 'tools/project_manager/show_current_flagged_words.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-good-word-suggestions', 'path': 'tools/project_manager/show_good_word_suggestions.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-good-word-suggestions-detail', 'path': 'tools/project_manager/show_good_word_suggestions_detail.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-possible-bad-words', 'path': 'tools/project_manager/show_project_possible_bad_words.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-stealth-scannos', 'path': 'tools/project_manager/show_project_stealth_scannos.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-wordcheck-stats', 'path': 'tools/project_manager/show_project_wordcheck_stats.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-wordcheck-usage', 'path': 'tools/project_manager/show_project_wordcheck_usage.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-word-context', 'path': 'tools/project_manager/show_word_context.php?projectid=projectID5e23a810ef693'},
    {'name': 'tools-pm-image-sources', 'path': 'tools/project_manager/show_image_sources.php'},
    {'name': 'tools-pm-specials', 'path': 'tools/project_manager/show_specials.php'},
    {'name': 'tools-pm-update-illos', 'path': 'tools/project_manager/update_illos.php?projectid=projectID5e23a810ef693&image=illo.png'},

    {'name': 'tools-prfs-for-mentors', 'path': 'tools/proofers/for_mentors.php?round_id=P3'},
    # TODO not installed
    {'name': 'tools-prfs-ctrl-frame', 'path': 'tools/proofers/ctrl_frame.php?round_id=P3&project_id=projectID5e23a810ef693'},
    {'name': 'tools-prfs-image-frame', 'path': 'tools/proofers/image_frame_std.php?projectid=projectID5e23a810ef693&proj_state=P3.proj_unavail&imagefile=001.png&page_state=P3.page_saved'},
    {'name': 'tools-prfs-text-frame', 'path': 'tools/proofers/text_frame_std.php?projectid=projectID5e23a810ef693&proj_state=P3.proj_unavail&imagefile=001.png&page_state=P3.page_saved'},
    {'name': 'tools-prfs-images-index', 'path': 'tools/proofers/images_index.php?project=projectID5e23a810ef693'},
    {'name': 'tools-prfs-mktable', 'path': 'tools/proofers/mktable.php'},
    # TODO make non-empty
    {'name': 'tools-prfs-my-projects', 'path': 'tools/proofers/my_projects.php'},
    {'name': 'tools-prfs-my-projects-available', 'path': 'tools/proofers/my_projects.php?round_view=available'},
    {'name': 'tools-prfs-my-projects-recent', 'path': 'tools/proofers/my_projects.php?round_view=recent'},
    {'name': 'tools-prfs-my-projects-active', 'path': 'tools/proofers/my_projects.php?round_view=active'},
    {'name': 'tools-prfs-my-projects-posted', 'path': 'tools/proofers/my_projects.php?round_view=posted'},
    # TODO make non-empty
    {'name': 'tools-prfs-my-suggestions', 'path': 'tools/proofers/my_suggestions.php'},
    {'name': 'tools-prfs-my-suggestions-impact', 'path': 'tools/proofers/my_suggestions.php?round_view=impact'},
    {'name': 'tools-prfs-my-suggestions-familiar', 'path': 'tools/proofers/my_suggestions.php?round_view=familiar'},
    {'name': 'tools-prfs-my-suggestions-style', 'path': 'tools/proofers/my_suggestions.php?round_view=style'},
    {'name': 'tools-prfs-my-suggestions-different', 'path': 'tools/proofers/my_suggestions.php?round_view=different'},
    # TODO make a valid state
    {
        'name': 'tools-prfs-processtext',
        'method': 'POST',
        'path': 'tools/proofers/processtext.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'proj_state': 'P3.proj_unavail',
            'imagefile': '001.png',
            'page_state': 'P3.page_saved',
            'button4': '1', # B_SWITCH_LAYOUT. NB this fails because not P3.page_temp
        },
    },
    # TODO check redirect URL
    {
        'name': 'tools-prfs-project-topic',
        'path': 'tools/proofers/project_topic.php?project=projectID5e23a810ef693',
        'expect_status': 302,
    },
    # TODO proof a valid state
    {'name': 'tools-prfs-proof', 'path': 'tools/proofers/proof.php?projectid=projectID5e23a810ef693&proj_state=P3.proj_unavail'},
    # TODO proof a valid state
    {'name': 'tools-prfs-proof-frame', 'path': 'tools/proofers/proof_frame.php?projectid=projectID5e23a810ef693&proj_state=P3.proj_unavail'},
    {
        'name': 'tools-prfs-report-bad-page',
        'method': 'POST',
        'path': 'tools/proofers/report_bad_page.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'proj_state': 'P3.proj_unavail',
            'imagefile': '001.png',
            'page_state': 'P3.page_saved',
        },
    },
    # TODO: Needs page_events or user_project_info entries to exercise properly
    {'name': 'tools-prfs-review-work', 'path': 'tools/proofers/review_work.php?username=teststeel'},
    # TODO: Needs projects in P3.proj_avail to exercise properly
    {'name': 'tools-prfs-round', 'path': 'tools/proofers/round.php?round_id=P3'},
    {'name': 'tools-prfs-srchrep', 'path': 'tools/proofers/srchrep.php'},

    {'name': 'tools-admin-convert-utf8', 'path': 'tools/site_admin/convert_project_table_utf8.php?projectid=projectID5e23a810ef693'},
    {
        'name': 'tools-admin-copy-pages',
        'method': 'POST',
        'path': 'tools/site_admin/copy_pages.php',
        'data': {
            'projectid_[from]': 'projectID5e23a810ef693',
            'projectid_[to]': 'projectID3141592653589',
            'from_image_[lo]': '004.png',
            'from_image_[hi]': '005.png',
            'page_name_handling': 'RENUMBER_PAGES',
            'transfer_notifications': 0,
            'add_deletion_reason': 0,
            'merge_wordcheck_data': 0,
            'repeat_project': 'NONE',
            'action': 'docopy',
        },
    },
    {
        'name': 'tools-admin-delete-pages',
        'method': 'POST',
        'path': 'tools/site_admin/delete_pages.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'from_image_[lo]': '004.png',
            'from_image_[hi]': '005.png',
            'action': 'check',
        },
    },
    # TODO Needs rules table to be useful
    {'name': 'tools-admin-display-rand-rules', 'path': 'tools/site_admin/displayrandrules.php'},
    # TODO Needs rules table to be useful
    {'name': 'tools-admin-manage-random-rules', 'path': 'tools/site_admin/manage_random_rules.php'},
    # TODO Needs a non-activated user to be useful
    {'name': 'tools-admin-edit-mail-address', 'path': 'tools/site_admin/edit_mail_address_for_non_activated_user.php'},
    {'name': 'tools-admin-index', 'path': 'tools/site_admin/index.php'},
    {'name': 'tools-admin-access-privileges', 'path': 'tools/site_admin/manage_site_access_privileges.php?username=teststeel'},
    {'name': 'tools-admin-charsuites', 'path': 'tools/site_admin/manage_site_charsuites.php'},
    # TODO Needs some site wordlists
    {'name': 'tools-admin-word-lists', 'path': 'tools/site_admin/manage_site_word_lists.php'},
    # TODO Needs some special day entries
    {'name': 'tools-admin-special-days', 'path': 'tools/site_admin/manage_special_days.php'},
    {
        'name': 'tools-admin-project-jump',
        'method': 'POST',
        'path': 'tools/site_admin/project_jump.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'new_state': 'F2.proj_unavail',
            'action': 'check',
        },
    },
    {'name': 'tools-admin-odd-values', 'path': 'tools/site_admin/projects_with_odd_values.php'},
    {'name': 'tools-admin-rename-pages', 'path': 'tools/site_admin/rename_pages.php?projectid=projectID3141592653589'},
    {
        'name': 'tools-admin-rename-pages-confirm',
        'method': 'POST',
        'path': 'tools/site_admin/rename_pages.php',
        'data': {
            'projectid': 'projectID3141592653589',
            'renumber_from_n': 'on',
            'renumbering_start': 123,
            'submit_button': 'Check renamings',
        },
    },
    # TODO Needs conflicts to be useful
    {'name': 'tools-admin-shared-postednums', 'path': 'tools/site_admin/shared_postednums.php'},
    # TODO Needs access_log entries to be useful
    {'name': 'tools-admin-access-log', 'path': 'tools/site_admin/show_access_log.php'},
    # TODO Needs overlapping word lists to be useful
    {'name': 'tools-admin-common-words', 'path': 'tools/site_admin/show_common_words_from_project_word_lists.php'},
    # TODO Needs news_items to be useful
    {'name': 'tools-admin-sitenews', 'path': 'tools/site_admin/sitenews.php'},
]

CRON_JOBS = [
    # TODO requires archive database
    #"ArchiveProjects",
    # TODO requires uploads trash configured
    #"CleanUploadsTrash",
    "ExtendSiteTallyGoals",
    #"ImportPGCatalog",  # moves a lot of data, don't run this
    "NotifyOldPP",
    "PruneJobLogs",
    "RecordProjectStateCounts",
    "RecordUserCounts",
    "SendSmoothreadingNotifications",
    "TakeTallySnapshots",
    "ToggleSpecialDayQueues",
];

# Every web test has a unique 'name', and every cron job name is already
# unique. Names must not contain commas or characters requiring shell
# escaping/quoting, since they can be given as a comma-separated list on
# the command line.
WEB_TESTS_BY_NAME = {test['name']: test for test in WEB_TESTS}
assert len(WEB_TESTS_BY_NAME) == len(WEB_TESTS), "Duplicate test name found in WEB_TESTS"
ALL_TEST_NAMES = set(WEB_TESTS_BY_NAME) | set(CRON_JOBS)

def get_site_config() -> dict:
    config = {}
    try:
        with open('pinc/site_vars.php', 'r') as site_vars:
            for l in site_vars:
                m = re.match(r"\$site_url\s*=\s*'(.*?)';", l)
                if m:
                    u = urlparse(m[1])
                    config.update({
                        'site_url': m[1].rstrip('/'),
                        'scheme': u.scheme,
                        'netloc': u.netloc, # hostname:port
                    })
                    continue
                m = re.match(r"\$code_dir\s*=\s*'(.*?)';", l)
                if m:
                    config.update({'code_dir': m[1]})
                    continue
    except FileNotFoundError:
        raise Exception("Script must be run from $_CODE_DIR (checkout dir)")
    return config

def start_server(host_port: str):
    """Start a PHP server in the background outputting to a log file.
    Return the Popen object."""
    # NB In theory we could use a pipe for stderr and just read from it
    # after every HTTP request, but in practice, Python's readline() API
    # is blocking, so there's no easy way to find the end of the log
    # output (because the last readline() will just block waiting for more
    # log entries). Just writing to a file on disc and manipulating that
    # is less elegant, but easier in practice.
    with open(SERVER_LOG, "w+") as logfile:
        return Popen(
            ['php', '-d', 'error_reporting=32767', '-d', 'display_errors=On', '-S', host_port],
            bufsize=1,
            stdin=DEVNULL,
            stdout=DEVNULL,
            stderr=logfile,
        )

def server_ready(site_url: str) -> bool:
    """Returns False if server doesn't respond correctly
    within 5 seconds & 10 retries"""
    for i in range(10):
        time.sleep(.5)
        url = f'{site_url}/SETUP/tests/smoketests/hello.php?i={i}'
        try:
            data, _, _, _ = request(Request(url))
            return data == b'Hello world\n'
        except URLError:
            pass
    return False

class NoRedirect(HTTPErrorProcessor):
    def http_response(self, request, response):
        return response
    def https_response(self, request, response):
        return response

class AllowInsecureCookiePolicy(DefaultCookiePolicy):
    def return_ok_secure(self, cookie, request):
        return True

# Create a URL handler that:
# 1 doesn't follow redirects
# 2 stores session cookies in memory
# 3 allows secure cookies to be sent over an insecure HTTP connection
jar = CookieJar(AllowInsecureCookiePolicy())
opener = build_opener(NoRedirect, HTTPCookieProcessor(jar))

def request(req: Request, data=None) -> Tuple[
        Optional[bytes],
        Optional[int],
        List[Tuple[str, str]],
        List[str]]:
    """Make an HTTP request to the server.
       Return the body, status, headers, and PHP logs"""
    # Truncate log so we only see logs that are in response to the next request
    with open(SERVER_LOG, 'w') as log:
        pass
    # Read from the log after the server responds
    with open(SERVER_LOG, 'r') as log:
        try:
            jar.add_cookie_header(req)
            response = opener.open(req, data=data)
            data = response.read()
            status = response.code
            headers = response.getheaders()
            jar.extract_cookies(response, req)
        except URLError:
            data, status, headers = None, None, []
        log_lines = log.readlines()
        return data, status, headers, log_lines

def test_failed(logs: List[str]) -> bool:
    """Are there are any PHP messages in the logs?"""
    return any(re.search('PHP (Notice|Warning|Fatal error|Deprecated)', l) for l in logs)

def login(config, username: str, password: str) -> bool:
    """Try to log in to the website and save the session cookie
    in the in-memory cookie jar."""
    req = Request(
        f'{config["site_url"]}/accounts/login.php',
        method='POST'
    )

    login_data = urlencode({'userNM': username, 'userPW': password})
    data, _, _, _ = request(req, login_data.encode())

    # We need to scrape the body to check if the login succeeded :(
    return b'Unable to authenticate' not in data

def check_error_detect(config) -> bool:
    """Does the log parsing correctly detect PHP notices, warnings, errors?"""
    url_base = config['site_url'] + '/SETUP/tests/smoketests/'
    for script in ['notice.php', 'warning.php', 'error.php', 'deprecated.php']:
        _, _, _, logs = request(Request(url_base + script))
        if not test_failed(logs):
            print(f"{script} PHP log wasn't detected!")
            print("\n".join(logs))
            return False
    return True

# For local debugging (to be able to see PHP logs), start a PHP
# server in a terminal window:
# php -S 127.0.0.1:12345 2> >(tee -a server.log >&2)
# and then run pageload_smoketest.py with
# --no-server --site-url http://127.0.0.1:12345
def main() -> int:
    p = argparse.ArgumentParser()
    p.add_argument('-n', '--no-server',
        help='''Don\'t automatically start a background PHP server.
        Requires --site-url for a server to connect to''',
        action='store_true'
    )
    p.add_argument('-v', '--verbose',
        help="Show response body from PHP code for every request",
        action='store_true'
    )
    p.add_argument('-u', '--username',
        help="The PGDP user to log in to the server with",
        type=str,
        required=True
    )
    p.add_argument('-p', '--password',
        help="The PGDP password to log in to the server with",
        type=str,
        required=True
    )
    p.add_argument('-k', '--api-key',
        help="The API key to send with every request",
        type=str,
        required=True
    )
    p.add_argument('-s', '--site-url',
        help="The web server to connect to. Eg http://127.0.0.1:12345/",
        type=str
    )
    p.add_argument('-l', '--list-tests',
        help="List the names of the available individual tests, and exit",
        action='store_true'
    )
    p.add_argument('-t', '--test',
        help='''Only run the named test(s). May be given multiple times,
        and/or as a comma-separated, wildcarded list. See --list-tests for the
        available names. Default: run all tests''',
        action='append'
    )
    args = p.parse_args()

    if args.list_tests:
        name_len = max(len(name) for name in ALL_TEST_NAMES)
        for i, name in enumerate(sorted(WEB_TESTS_BY_NAME), 1):
            test = WEB_TESTS_BY_NAME[name]
            print(f"{i:3d}. {name:<{name_len}} {test.get('method', 'GET')} {test['path']}")
        for i, name in enumerate(sorted(CRON_JOBS), len(WEB_TESTS_BY_NAME) + 1):
            print(f"{i:3d}. {name:<{name_len}} cron job")
        return 0

    selected_names = []
    if args.test:
        for arg in args.test:
            for pattern in arg.split(','):
                matches = fnmatch.filter(ALL_TEST_NAMES, pattern)
                if not matches:
                    print(f"Invalid test name '{pattern}'")
                    print("Run with --list-tests to see the available names")
                    return 1
                for name in matches:
                    if name not in ALL_TEST_NAMES:
                        print(f"Invalid test name '{name}'")
                        print("Run with --list-tests to see the available names")
                        return 1
                    selected_names.append(name)

    if not selected_names:
        web_tests = WEB_TESTS
        cron_jobs = CRON_JOBS
    else:
        web_tests = [test for name, test in WEB_TESTS_BY_NAME.items() if name in selected_names]
        cron_jobs = [name for name in CRON_JOBS if name in selected_names]

    ret = 0
    config = get_site_config()

    if not args.no_server:
        print("Starting php server...")
        php_server = start_server(config['netloc'])
        if not server_ready(config['site_url']):
            return 1
        print(f"php server ready pid={php_server.pid}")

        if not check_error_detect(config):
            return 1
        print("Log parsing OK")

    if args.site_url:
        config['site_url'] = args.site_url

    if not login(config, args.username, args.password):
        print("Failed to get session cookie. Check username/password.")
        return 1
    print("Login succeeded. Cookie jar contents:")
    for c in jar:
        print(f"    {c}")

    for test in web_tests:
        method = test.get('method', 'GET')
        req_data = test.get('data')
        req_json = test.get('json')
        if req_data is not None and req_json is not None:
            print(f"Error: Request cannot have both urlencoded and json "
                  "encoded body:\n    {method} {path} {req_data} {req_json}")
            ret = 1
            break
        path = test['path']
        expect_status = test.get('expect_status', 200)
        if isinstance(expect_status, int):
            expect_status = [expect_status]

        req = Request(
            config['site_url'] + '/' + path,
            method=method,
            headers={'X-API-KEY': args.api_key},
        )
        print(f"Running test '{test['name']}'")
        if req_data is not None:
            print(f"{method} {path} {req_data}")
        elif req_json is not None:
            print(f"{method} {path} {req_json}")
        else:
            print(f"{method} {path}")

        if req_data is not None:
            req_data = urlencode(req_data).encode()
        if req_json is not None:
            req_data = json.dumps(req_json).encode()
        data, status, _, logs = request(req, req_data)

        if args.verbose:
            if data[0:4] == 'PK\3\4':
                print("ZIP file...")
            else:
                print(data.decode())
        if status not in expect_status or test_failed(logs):
            print(f'Status: {status} (expected {expect_status})')
            print('\n'.join(logs))
            ret = 1
            break

    for test in cron_jobs:
        print(f"Cronjob: {test}")
        cmd_result = run(
            [
                "php",
                os.path.join(config['code_dir'], "crontab", "run_background_job.php"),
                test,
                "true"
            ],
            stdout=PIPE,
            stderr=STDOUT,
            encoding='utf-8'
        )
        if cmd_result.returncode != 0 or not 'Succeeded: true' in cmd_result.stdout or test_failed([cmd_result.stdout]):
            print(f'Return code: {cmd_result.returncode}')
            print(f'Output:\n', cmd_result.stdout)
            ret = 1
            break

    if not args.no_server:
        php_server.send_signal(signal.SIGINT)

    return ret

if __name__ == '__main__':
    sys.exit(main())
