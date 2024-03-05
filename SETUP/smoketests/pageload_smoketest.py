#!/usr/bin/env python3

import argparse
import re
import signal
import sys
import time

from http.cookiejar import CookieJar, DefaultCookiePolicy
from subprocess import Popen, DEVNULL
from urllib.error import URLError
from urllib.parse import urlencode, urlparse
from urllib.request import build_opener, HTTPCookieProcessor, HTTPErrorProcessor, Request

from typing import List, Optional, Tuple

SERVER_LOG = "server.log"

NOLOGIN_TESTS = [
    {'path': 'accounts/addproofer.php'},
    {'path': 'credits.php'},
    {'path': 'default.php'},
    {'path': 'list_etexts.php'},
    {'path': 'sitemap.php'},
]

BASE_TESTS = [
    {'path': 'activity_hub.php'},
    {'path': 'pastnews.php'},
    {'path': 'pophelp.php?category=teams&name=edit_teamname'},
    {'path': 'project.php?id=projectID5e23a810ef693'},
    {'path': 'tasks.php'},
    {'path': 'userprefs.php'},
    {'path': 'accounts/login_failure.php', 'expect_status': 302},
    {'path': 'accounts/require_login.php'},
]

API_TESTS = [
    {'path': 'api/index.php?url=v1/projects'},
    {'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693'},
    {'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/wordlists/good'},
    {'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/holdstates'},
    {'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/pages'},
    {'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/pagedetails'},
    {'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/pages/001.png/pagerounds/P1'},
    {'path': 'api/index.php?url=v1/projects/projectID5e23a810ef693/transitions'},
    {'path': 'api/index.php?url=v1/projects/difficulties'},
    {'path': 'api/index.php?url=v1/projects/genres'},
    {'path': 'api/index.php?url=v1/projects/states'},
    {'path': 'api/index.php?url=v1/projects/pagerounds'},
    {'path': 'api/index.php?url=v1/projects/charsuites'},
    {'path': 'api/index.php?url=v1/projects/specialdays'},
    {'path': 'api/index.php?url=v1/projects/imagesources'},
    {'path': 'api/index.php?url=v1/projects/holdstates'},

    {'path': 'api/index.php?url=v1/queues'},
    {'path': 'api/index.php?url=v1/queues/500'},
    {'path': 'api/index.php?url=v1/queues/500/stats'},
    {'path': 'api/index.php?url=v1/queues/500/projects'},

    {'path': 'api/index.php?url=v1/stats/site'},
    {'path': 'api/index.php?url=v1/stats/site/projects/stages'},
    {'path': 'api/index.php?url=v1/stats/site/projects/states'},
    {'path': 'api/index.php?url=v1/stats/site/rounds'},
    {'path': 'api/index.php?url=v1/stats/site/rounds/P3'},
]

FAQ_TESTS = [
    {'path': 'faq/doc-copy.php'},
    {'path': 'faq/feeds_sdk/index.php'},
    {'path': 'faq/font_sample.php'},
    {'path': 'faq/privacy.php'},
    {'path': 'faq/prooffacehelp.php'},
    {'path': 'faq/simple_proof_rules.php'},
    {'path': 'faq/site_progress_snapshot_legend.php'},
    {'path': 'faq/translate.php'},
]

MISC_TESTS = [
    # 'feeds/backend.php' # Disabled. It writes to a file
    {'path': 'locale/debug_ui_language.php'},
    {'path': 'locale/translators/index.php'},
]

QUIZ_TESTS = [
    {'path': 'quiz/start.php'},
    {'path': 'quiz/generic/hints.php?quiz_page_id=p_greek_4&error=G_n_u&number=1'},
    {'path': 'quiz/generic/main.php?quiz_page_id=p_greek_1'},
    {'path': 'quiz/generic/orig.php?quiz_page_id=p_greek_2'},
    {'path': 'quiz/generic/proof.php?quiz_page_id=p_greek_3'},
    {'path': 'quiz/generic/returnfeed.php?quiz_page_id=p_thorn'},
    {'path': 'quiz/generic/right.php?quiz_page_id=p_fraktur'},
    {'path': 'quiz/generic/wizard/default_messages.php'},
    {'path': 'quiz/generic/wizard/start.php'},
    {'path': 'quiz/generic/wizard/new_quiz.php'},
    # TODO: Needs SESSION data
    #'quiz/generic/wizard/general.php',
    #'quiz/generic/wizard/checks.php',
    #'quiz/generic/wizard/messages.php',
    #'quiz/generic/wizard/output.php',
    #'quiz/generic/wizard/output_quiz.php',
    #'quiz/generic/wizard/quiz_pages.php',

    {'path': 'quiz/tuts/tut_p_aeoe_1.php'},
    {'path': 'quiz/tuts/tut_p_aeoe_2.php'},
    {'path': 'quiz/tuts/tut_p_basic_1.php'},
    {'path': 'quiz/tuts/tut_p_basic_2.php'},
    {'path': 'quiz/tuts/tut_p_basic_3.php'},
    {'path': 'quiz/tuts/tut_p_basic_4.php'},
    {'path': 'quiz/tuts/tut_p_basic_5.php'},
    {'path': 'quiz/tuts/tut_p_fraktur.php'},
    {'path': 'quiz/tuts/tut_p_greek_1.php'},
    {'path': 'quiz/tuts/tut_p_greek_2.php'},
    {'path': 'quiz/tuts/tut_p_greek_3.php'},
    {'path': 'quiz/tuts/tut_p_greek_4.php'},
    {'path': 'quiz/tuts/tut_p_greek_5.php'},
    {'path': 'quiz/tuts/tut_p_mod1_1.php'},
    {'path': 'quiz/tuts/tut_p_mod1_2.php'},
    {'path': 'quiz/tuts/tut_p_mod1_3.php'},
    {'path': 'quiz/tuts/tut_p_mod1_4.php'},
    {'path': 'quiz/tuts/tut_p_mod1_5.php'},
    {'path': 'quiz/tuts/tut_p_mod2_1.php'},
    {'path': 'quiz/tuts/tut_p_mod2_2.php'},
    {'path': 'quiz/tuts/tut_p_mod2_3.php'},
    {'path': 'quiz/tuts/tut_p_mod2_4.php'},
    {'path': 'quiz/tuts/tut_p_mod2_5.php'},
    {'path': 'quiz/tuts/tut_p_old_1.php'},
    {'path': 'quiz/tuts/tut_p_old_2.php'},
    {'path': 'quiz/tuts/tut_p_old_3.php'},
    {'path': 'quiz/tuts/tut_p_thorn.php'},
]

TEAMS_TESTS = [
    # NB performs an action
    {'path': 'stats/members/jointeam.php?tid=44', 'expect_status': [200, 302]},
    # NB performs an action
    {'path': 'stats/members/quitteam.php?tid=44', 'expect_status': [200, 302]},
    # Adds a forum post!
    {'path': 'stats/teams/team_topic.php?team=44', 'expect_status': 302},
    {'path': 'stats/members/mbr_list.php?tid=44'},
    {'path': 'stats/members/mbr_xml.php?username=teststeel'},
    {'path': 'stats/members/mdetail.php?id=1'},
    {'path': 'stats/teams/new_team.php'},
    {'path': 'stats/teams/tdetail.php?tid=44'},
    {'path': 'stats/teams/teams_xml.php?tid=44'},
    {'path': 'stats/teams/tedit.php?tid=44'},
    {'path': 'stats/teams/tlist.php'},
]

STATS_TESTS = [
    {'path': 'stats/PP_unknown.php'},
    {'path': 'stats/equilibria.php'},
    {'path': 'stats/misc_stats1.php?tally_name=F1&start=2024-01&end=2024-02'},
    {'path': 'stats/misc_user_graphs.php'},
    {'path': 'stats/pages_in_states.php'},
    {'path': 'stats/pages_proofed_graphs.php?tally_name=P3'}, # Slow to evaluate!
    {'path': 'stats/percent_users_who_proof.php'},
    {'path': 'stats/pm_stats.php'},
    {'path': 'stats/pp_stage_goal.php'},
    {'path': 'stats/pp_stats.php'},
    {'path': 'stats/ppv_stats.php'},
    {'path': 'stats/projects_Xed_graphs.php?which=posted'},
    {'path': 'stats/proof_stats.php?tally_name=F2'},
    {'path': 'stats/release_queue.php'},
    {'path': 'stats/requested_books.php'},
    {'path': 'stats/round_backlog.php'},
    {'path': 'stats/round_backlog_days.php'},
    {'path': 'stats/stats_central.php'},
    {'path': 'stats/user_logon_graphs.php'},
]

STYLES_TESTS = [
    {'path': 'styles/design_philosophy.php'},
    {'path': 'styles/style_demo.php'},
]

TOOLS_TESTS = [
    {
        'method': 'POST',
        'path': 'tools/change_sr_commitment.php',
        'data': {'projectid': 'projectID5e23a810ef693','action': 'commit'}
    },
    {
        'method': 'POST',
        'path': 'tools/changestate.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'curr_state': 'P3.proj_avail',
            'next_state': 'P3.proj_unavail',
            'confirmed': 'yes',
        },
    },
    {'path': 'tools/charsuites.php'},
    {'path': 'tools/download_images.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/extend_sr.php?project=projectID5e23a810ef693&days=10'},
    {
        'method': 'POST',
        'path': 'tools/modify_access.php',
        'data': {
            'subject_username': 'admin',
            'F2|grant': 'on',
        },
    },
    {
        'method': 'POST',
        'path': 'tools/modify_access.php',
        'data': {
            'subject_username': 'admin',
            'F2|revoke': 'on',
        },
    },
    {'path': 'tools/page_browser.php?project=projectID5e23a810ef693&imagefile=001.png'},
    {'path': 'tools/pending_access_requests.php'},
    {'path': 'tools/pool.php?pool_id=PP'},
    {
        'method': 'POST',
        'path': 'tools/remove_project_hold.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'curr_state': 'P3.proj_avail',
            'return_uri': '',
        },
        'expect_status': 302,
    },
    {'path': 'tools/request_access.php?stage_id=F2'},
    {'path': 'tools/search.php?show=search&title=A'},
    {
        'method': 'POST',
        'path': 'tools/set_project_event_subs.php',
        'data': {'projectid': 'projectID5e23a810ef693', 'posted': 'on'},
    },
    {
        'method': 'POST',
        'path': 'tools/set_project_holds.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'P3_proj_avail': 'on',
            'return_uri': ''
        },
    },
    {
        'method': 'POST',
        'path': 'tools/setlangcookie.php',
        'data': {'lang': 'en-US'},
        'expect_status': 302,
    },
]

TOOLS_AUTHORS_TESTS = [
    # Show form
    {'path': 'tools/authors/add.php'},
    {
        'method': 'POST',
        'path': 'tools/authors/add.php',
        'data': {
            'last_name': 'Franklin',
            'bday': 17,
            'bmonth': 1,
            'byear': 1706,
            'byearRadio': 1,
            'dday': 17,
            'dmonth': 4,
            'dyear': 1790,
            'dyearRadio': 1,
        },
    },
    {'path': 'tools/authors/addbio.php?bio_id=1&author_id=1'},
    # Disabled until #1140 is committed.
    #{'path': 'tools/authors/author.php?author_id=1'},
    {'path': 'tools/authors/authorxml.php'},
    {'path': 'tools/authors/bio.php'},
    {'path': 'tools/authors/bio.php?bio_id=1'},
    {'path': 'tools/authors/bioxml.php'},
    {'path': 'tools/authors/listing.php'},
    {'path': 'tools/authors/listing.php?last_name=Smith&other_names'},
    {'path': 'tools/authors/manage.php'},
    {'path': 'tools/authors/manage.php?last_name=Smith&other_names'},
]

TOOLS_POST_PROOFERS_TESTS = [
    {
        'method': 'POST',
        'path': 'tools/post_proofers/postcomments.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'comments': 'It was a dark and stormy night',
        },
    },
    {'path': 'tools/post_proofers/ppv_report.php?project=projectID5e23a810ef693'},
    {'path': 'tools/post_proofers/smooth_reading.php'},
]

TOOLS_PROJECT_MANAGER_TESTS = [
    # TODO add project in P1.unavail
    {'path': 'tools/project_manager/add_files.php?project=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/automodify.php'},
    {'path': 'tools/project_manager/bad_bytes_explainer.php'},
    {'path': 'tools/project_manager/clearance_check.php'},
    {'path': 'tools/project_manager/diff.php?project=projectID5e23a810ef693&L_round=P1&R_round=P2&image=001.png'},
    # (deliberately) not clicking 'Confirm'...
    {'path': 'tools/project_manager/edit_pages.php?projectid=projectID5e23a810ef693&operation=clear&selected_pages[001.png]=on'},
    # TODO project dir doesn't exist
    {'path': 'tools/project_manager/edit_project_word_lists.php?projectid=projectID5e23a810ef693'},
    # TODO other actions
    {'path': 'tools/project_manager/editproject.php?project=projectID5e23a810ef693&action=edit'},
    {'path': 'tools/project_manager/external_catalog_search.php?action=show_query_form'},
    # TODO yaz
    #{'path': 'tools/project_manager/external_catalog_search.php?action=do_search_and_show_hits'},
    {'path': 'tools/project_manager/generate_post_files.php?projectid=projectID5e23a810ef693&round_id=P3&which_text=EQ'},
    {'path': 'tools/project_manager/handle_bad_page.php?projectid=projectID5e23a810ef693&image=001.png'},
    {'path': 'tools/project_manager/manage_image_sources.php?action=show_sources'},
    {'path': 'tools/project_manager/marc_inspector.php?rec=H4sIAAAAAAAAA23QwQrCMAwG4FcJPSmI_Enbrc0uvko2PQgq4rzJ3t2uQ2GwXpo0_b9CTVv9XBWdqSzFqAx1O3-4Xex8ee1dd1UupxLUATnkh93JRABJmYxCBFw3zZdM-We0lQB4X2ey8uU_LNvp_0LJeJ9ahtSM3_Li4oVNL649btRxTgmRBTmKyDHXdNyS0yI3m3Jay6H8UHE9p5Fzw1TW-CTrh1L0cweAQIO9iYo6fQG-RwV6ZgEAAA'},
    {'path': 'tools/project_manager/page_compare.php?project=projectID5e23a810ef693&L_round=P1&R_round=P2'},
    {'path': 'tools/project_manager/page_detail.php?project=projectID5e23a810ef693'},
    # Needs project dir and pngcheck
    #{'path': 'tools/project_manager/project_quick_check.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/projectmgr.php?show=user_avail'},
    {'path': 'tools/project_manager/projectmgr.php?show=user_active'},
    {'path': 'tools/project_manager/projectmgr.php?show=user_all'},
    {'path': 'tools/project_manager/projectmgr.php?show=search_form'},
    {'path': 'tools/project_manager/remote_file_manager.php'},
    {'path': 'tools/project_manager/show_adhoc_word_details.php?projectid=projectID5e23a810ef693'},
    {
        'method': 'POST',
        'path': 'tools/project_manager/show_adhoc_word_details.php',
        'data': {
            'projectid': 'projectID5e23a810ef693',
            'queryWordText': 'the\nwho',
            'freqCutoff': 1
        },
    },
    {'path': 'tools/project_manager/show_all_good_word_suggestions.php'},
    # TODO add word lists to project to exercise this better
    {'path': 'tools/project_manager/show_current_flagged_words.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/show_good_word_suggestions.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/show_good_word_suggestions_detail.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/show_project_possible_bad_words.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/show_project_stealth_scannos.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/show_project_wordcheck_stats.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/show_project_wordcheck_usage.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/show_word_context.php?projectid=projectID5e23a810ef693'},
    {'path': 'tools/project_manager/show_image_sources.php'},
    {'path': 'tools/project_manager/show_specials.php'},
    # TODO Needs a projects dir to work.
    #{'path': 'tools/project_manager/update_illos.php?projectid=projectID5e23a810ef693&image=001.png'},
]

TESTS = (
    NOLOGIN_TESTS +
    BASE_TESTS +
    API_TESTS +
    FAQ_TESTS +
    MISC_TESTS +
    QUIZ_TESTS +
    TEAMS_TESTS +
    STATS_TESTS +
    STYLES_TESTS +
    TOOLS_TESTS +
    TOOLS_AUTHORS_TESTS +
    TOOLS_POST_PROOFERS_TESTS +
    TOOLS_PROJECT_MANAGER_TESTS
)

# {'path': 'tools/proofers/ctrl_frame.php'},
# {'path': 'tools/proofers/for_mentors.php'},
# {'path': 'tools/proofers/greek2ascii.php'},
# {'path': 'tools/proofers/hiero/index.php'},
# {'path': 'tools/proofers/image_frame_std.php'},
# {'path': 'tools/proofers/images_index.php'},
# {'path': 'tools/proofers/mktable.php'},
# {'path': 'tools/proofers/my_projects.php'},
# {'path': 'tools/proofers/my_suggestions.php'},
# {'path': 'tools/proofers/processtext.php'},
# {'path': 'tools/proofers/project_topic.php'},
# {'path': 'tools/proofers/proof.php'},
# {'path': 'tools/proofers/proof_frame.php'},
# {'path': 'tools/proofers/report_bad_page.php'},
# {'path': 'tools/proofers/review_work.php'},
# {'path': 'tools/proofers/round.php'},
# {'path': 'tools/proofers/srchrep.php'},
# {'path': 'tools/proofers/text_frame_std.php'},
#
# {'path': 'tools/site_admin/convert_project_table_utf8.php'},
# {'path': 'tools/site_admin/copy_pages.php'},
# {'path': 'tools/site_admin/delete_pages.php'},
# {'path': 'tools/site_admin/displayrandrules.php'},
# {'path': 'tools/site_admin/edit_mail_address_for_non_activated_user.php'},
# {'path': 'tools/site_admin/index.php'},
# {'path': 'tools/site_admin/manage_random_rules.php'},
# {'path': 'tools/site_admin/manage_site_access_privileges.php'},
# {'path': 'tools/site_admin/manage_site_charsuites.php'},
# {'path': 'tools/site_admin/manage_site_word_lists.php'},
# {'path': 'tools/site_admin/manage_special_days.php'},
# {'path': 'tools/site_admin/project_jump.php'},
# {'path': 'tools/site_admin/projects_with_odd_values.php'},
# {'path': 'tools/site_admin/rename_pages.php'},
# {'path': 'tools/site_admin/shared_postednums.php'},
# {'path': 'tools/site_admin/show_access_log.php'},
# {'path': 'tools/site_admin/show_common_words_from_project_word_lists.php'},
# {'path': 'tools/site_admin/sitenews.php'},

def get_site_config() -> dict:
    config = {}
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
            ['php', '-S', host_port],
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
        url = f'{site_url}/SETUP/smoketests/hello.php?i={i}'
        try:
            data, _, _, _ = request(Request(url))
            return data == b'Hello world\n'
        except URLError:
            pass
    return False

# For local debugging run the following in a terminal window
# php -S 127.0.0.1:12345 2> >(tee -a server.log >&2)

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
    return any(re.search('PHP (Notice|Warning|Fatal error)', l) for l in logs)

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
    url_base = config['site_url'] + '/SETUP/smoketests/'
    for script in ['notice.php', 'warning.php', 'error.php']:
        _, _, _, logs = request(Request(url_base + script))
        if not test_failed(logs):
            print(f"{script} PHP log wasn't detected!")
            print("\n".join(logs))
            return False
    return True

def main() -> int:
    p = argparse.ArgumentParser()
    p.add_argument('-n', '--no-server', action='store_true')
    p.add_argument('-v', '--verbose', action='store_true')
    p.add_argument('-u', '--username', type=str, required=True)
    p.add_argument('-p', '--password', type=str, required=True)
    p.add_argument('-k', '--api-key', type=str, required=True)
    p.add_argument('-s', '--site-url', type=str)
    args = p.parse_args()

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

    for test in TESTS:
        method = test.get('method', 'GET')
        req_data = test.get('data')
        path = test['path']
        expect_status = test.get('expect_status', 200)
        if isinstance(expect_status, int):
            expect_status = [expect_status]

        req = Request(
            config['site_url'] + '/' + path,
            method=method,
            headers={'X-API-KEY': args.api_key},
        )
        if req_data is not None:
            print(f"{method} {path} {req_data}")
        else:
            print(f"{method} {path}")

        if req_data is not None:
            req_data = urlencode(req_data).encode()
        data, status, _, logs = request(req, req_data)
        if args.verbose:
            print(data.decode())
        if status not in expect_status or test_failed(logs):
            print(f'Status: {status}')
            print('\n'.join(logs))
            ret = 1
            break

    if not args.no_server:
        php_server.send_signal(signal.SIGINT)

    return ret

if __name__ == '__main__':
    sys.exit(main())
