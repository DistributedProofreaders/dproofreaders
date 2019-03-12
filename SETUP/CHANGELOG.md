# Changelog

Major changes to this project are documented here. For minor changes,
see the git history.


## R??????
Scripts supporting this upgrade are in `SETUP/upgrade/13`

* Updated minimum PHP version to 7.0 (cpeel)


## R201903
Scripts supporting this upgrade are in `SETUP/upgrade/12`

* Support Ubuntu 16.04 middleware (cpeel)
* Support jpgraph version 4.2.0 (cpeel)
* Move manager and sitemanager columns to usersettings (cpeel)
* `$writeBIGtable` support, including `project_pages` table, has been
  removed (cpeel)
* Proofreading and Formatting Guidelines translations have been moved to
  language-specific subdirectories under `faq/` (cpeel)
* Large file uploads in `remote_file_manager.php` (cpeel)
* View page differences without formatting (70ray)
* Cleanup of users table (cpeel)
* Task Center updates (mlazaric)
* RSS feed improvements (mlazaric)
* Smooth Reading transition updates and notifications (mlazaric)
* DPCustomMono2 available as a web font (cpeel)
* Gravatar support (cpeel)
* User registrations ask about how they found the site (cpeel)
* Server time now displayed on PM page (70ray)
* My Project links no longer reuse windows (70ray)


_Usernames below this point refer to SourceForge usernames, rather than
GitHub usernames._

## R201802
Scripts supporting this upgrade are in `SETUP/upgrade/11`

* Remove support for phpBB 2.x and MySQL 5.1 (cpeel)
* Support jpgraph version 4.1.0 (cpeel)
* Theming moved entirely to CSS, standardized look-and-feel across site, site
  is largely HTML5 compliant (Happy5214, rp31, cpeel)
* New PM page separate from Search page (rp31, cpeel)
* Project Quick Check code brought over from noncvs (cpeel)
* See `git log R201707 R201802` for full set of changes.


## R201707
Scripts supporting this upgrade are in `SETUP/upgrade/10`

* Support phpBB versions 3.1 and 3.2 (cpeel)
* Support jpgraph version 4.0.2 (cpeel)
* Moved to mysqli extention (cpeel)
  This should allow the code to run on PHP 7.x but that has not been tested.
* Full French translation of the UI (srjfoo and other volunteers)
* See `git log R201701 R201707` for full set of changes.


## R201701
Scripts supporting this upgrade are in `SETUP/upgrade/09`

* New Format Preview functionality (rp31)
* Removed dependency on magic quotes allowing PHP 5.4 and later support (cpeel)
* See `git log R201601 R201701` for full set of changes.


## R201601
Scripts supporting this upgrade are in `SETUP/upgrade/08`

### Major changes
* phpBB 3.0 is supported in addition to 2.x (cpeel)
* Replace spellcheck with WordCheck; lots of functionality
  (mainly cpeel, some jmdyck)
* remote_file_manager.php replaces FTP for uploading project images/texts
  (bfoley, Donovan, jmdyck, cpeel)
* Redesigned translation center; see faq/translate.php (lvl, cpeel)
* Ability for site-specific protection against bot registrations;
  see site_registration_protection.php.example (cpeel)

### Other changes
* numerous places: use new config variables SITE_NAME, SITE_ABBREVIATION, SITE_SIGNOFF (mike_cie)
* review_work.php: Allow members at large to review their own work. (mike_cie)
* LPage.inc: use new config variable: PRECEDING_PROOFER_RESTRICTION (mike_cie)
* alter_marc_records_table.php: Add a primary key on projectid. (mike_cie)
* Project.inc: use new config variable PUBLIC_PAGE_DETAILS (mike_cie)
* my_projects.php: Echo a count of projects before each table. (jmdyck)
* toolbox.inc: Add a 'remove_markup' button (mike_cie)
* project.php: Extend the PPer's SR-related powers to sitemanagers. (jmdyck)
* various places: use new config variable PHP_CLI_EXECUTABLE. (jmdyck)
* db: Add indexes to page_events table (mike_cie)
* tasks.php: Update the browser list (donovang)
* DPage.inc: Don't use SQL function LOAD_FILE() (jmdyck)
* DPage.inc: Normalize every page-text before saving it in the db (jmdyck)
* default.php, theme.inc: Redesign front page (donovang)
* editproject.php: Allow site managers to change PM when editing project details (lmpryor)
* project.php, editproject.php: Add ability to clone a project (lmpryor)
* faq: Add Spanish translation of proofreading guidelines (cbgrf via mike_cie)
* proofreading interface javascript: Remove obsolete code, tweak remaining code (jmdyck)
* dp.cron: Invoke automodify.php via lynx (jmdyck)
* proofreading interface: Buttons to add <f> and <g> markup (txwikinger)
* proofreading interface: Improvements re resizing (cpeel)
* various places: Introduce project event subscriptions (jmdyck)
* stealth scanno lists: Updates (big_bill via lmpryor)
* page_detail.php: Add "Just my pages" link to all-pages view (weinsteinj)
* diff.php: Give name of project and provide link back to project page (lmpryor)
* editprojects.php, projects.php: Capture timestamp of last change to project comments (jmdyck)
* stages.inc: Add evaluation_criteria property (lmpryor)
* review_work.php: Numerous improvements (lmpryor, cpeel)
* projectmgr.php: Add "Avail. Pages" column (jmdyck)
* various: Add PM preference re whether project is auto-sent to PP pool (donovang)
* dp_proof.js: Footnote button now handles 2- and 3-digit footnote numbers (mike_cie)
* diff.php: Add ability to jump to diffs for other pages (lmpryor)
* member.inc: Add icon for mentors (donovang)
* pg.inc: Update to reflect PG's website restructuring (donovang)
* diff.php: Show proofreader names if user is entitled to see them (lmpryor)
* project.php: When project is avail for PP, show PP comments to prospective PPers (lmpryor)
* my_projects.php: Handle merged-deleted projects (lmpryor)
* page_detail.php: Add select_by_round (lmpryor)
* pending_access_requests.php: Various improvements (lmpryor)
* prefs_options.inc: Add Monaco (Mac) & Lucida Console (Windows) as font prefs (bpfoley)
* displayimage.php: Give browser hints for prefetching (bpfoley)
* tasks.php: Verify user has permission to make request (bpfoley)
* installation.txt: Various improvements (jmdyck)
* list_etexts.php: Make gold/silver/bronze lists pageable (bpfoley)
* diff.php: Add link to page image (donovang)
* document.php, proofreading_guidelines.php: Clarifications & error-fixes (acunning40 via donovang)
* site_vars.php: Check that site has magic_quotes_gpc is turned on (bpfoley)
* various places: Adapt code to run under PHP >= 5.2 (bpfoley)
* special_colors.inc: Rework presentation of Special Days legend (donovang)
* archiving.inc: When archiving a project, also archive other data (jmdyck)
* page_table.inc: Merge the per-round 'Clear' columns into one column at the right (cpeel)
* various places: Use abbreviated project-state names where screen space is scarce (jmdyck)
* project.php: Make the project page (in reduced form) accessible to guests (jmdyck)
* smooth_reading.php: Remove direct download link, direct to project page (jmdyck)
* dp_scroll.js, ctrl_frame.php: Fix bug re initializing JS variables (jmdyck)
* stats/jpgraph_files: Updated to make efficient use of jpgraph cache (cpeel)
* post_files.inc: Updated TEI XML output at PP stage (rwst)
* proofreading interface: Prevent image from scaling down too far (weinsteinj)
* base.inc: New include file for all .php pages to use; enforces maintenance mode and enables proper gettext() initialization (cpeel)
* dp_main.inc: Deprecated by base.inc and require_login() (cpeel)
* theme.inc: Refactor how headings and footers are output (cpeel, bfoley)
* various places: _() wrappers around strings to allow translation (lvl, cpeel)
* various places: Miscellaneous improvements (jmdyck, lmpryor, cpeel, donovang, mike_cie, rfarinha)


## R200609
Scripts supporting this upgrade are in `SETUP/upgrade/07`

* Yellow highlight on punctuation in spellcheck (donovang)
* Projects with (P3 Qual) in title not archived for a longer period than other books (jmdyck)
* Prevent access to restricted pages by url-tweaking (jmdyck)
* BEGIN projects served by username in P2 (jmdyck)
* "Emergency valve release" overrides author/PM restriction when queues do not release (big_bill_boy)
* New project search options (special day, checkedoutby); public project search (jmdyck)
* Footnote button parses footnote anchor in PI (mike_cie)
* Updates to proofreading and formatting guidelines in CVS (Miller/jmdyck)
* Allow PPVers to see postcomments when browsing projects available for PPV (mike_cie)
* Code-tidiness in theme.inc (jmdyck)
* Simplify/tidy/de-bug spellcheck code (jmdyck)
* "Save and do another" button now available when editing a single page (jmdyck)
* Simplify/tidy/de-bug PI (jmdyck)
* Simplify/tidy/de-bug bad page handling (jmdyck)
* Enhance Data, Logic, and Presentation of proofreading pages (jmdyck)
* Resolve page cookie problems (jmdyck)
* Undo and non-regex search added to PI
* My Projects stopped showing deleted (i.e., merged remnants) projects (jmdyck)
* Stage icons displayed if present in d/ (jmdyck)
* Warn (error?) if image file under 100 bytes at loading (donovang)
* Extension of post-files facility (jmdyck)
* Smoothreading commitments (txwikinger)
* Donation recipient changed from PGLAF to DPF (jmdyck)
* Simplify/tidy/de-bug project transitions (jmdyck)
* Round skipping (jmdyck)
* Buttons on project page for state transitions (jmdyck)
* Diff can compare any two versions of a page (jmdyck)
* Suppress skipped rounds from page table display (jmdyck)
* Round number column in queue_defns table changed to round ID (jmdyck)
* Simplify/tidy/de-bug site news. Show update news link even when no news exists. (jmdyck)
* mentoring-related properties added to rounds (jmdyck)
* [New round and related scripts] (jmdyck)
* Allow post files to be generated from mix of current or previous rounds (jmdyck)
* Three new genres added -- Engineering, Geography, Picture Book (JulietS/mike_cie)
* Transition to go back 1 round added (jmdyck)
* Link to a round's release queues and list of queues a book is in added to project page (jmdyck)
* project_events table added to log project-related events (jmdyck)
* Project history added to project page (jmdyck)
* Workflow diagram updated (acunning40/jmdyck)
* deletion_reason added to projects table (jmdyck)
* update_birthday_queues script updated as onoff_special_event_queues (jmdyck)
* Increased accuracy of active users count on welcome page (jmdyck)
* Bugfixes and refactoring of phpBB code (jmdyck)
* Add 'toolboxes' for use in the ctrl_frame in PI (jmdyck)
* Allow upload of work-in-progress when returning to PP pool (lmpryor)
* Allow on-the-fly generation of anonymised post files (lmpryor)
* Auto posts to project thread on specific events (jmdyck)
* Refactoring of Project class (jmdyck)
* Included logo graphics had PG removed (donovang)
* New "About DPF" page (donovang)
* More info included in non-activated users page (lmpryor)
* Site variables moved from v_site.inc to site_vars.php for security (jmdyck)
* Record last time of project info change (jmdyck)
* Comments from site_vars.php moved to configuration.sh, and additional comments added (jmdyck)
