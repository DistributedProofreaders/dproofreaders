# Changelog

Major changes to this project are documented here. For minor changes,
see the git history.

**If you are upgrading from a release before R202009, you must upgrade to
[R202009](https://github.com/DistributedProofreaders/dproofreaders/releases/tag/R202009)
first before upgrading to R202102 or later releases.**

## R??????
Scripts supporting this upgrade are in `SETUP/upgrade/23`

### Notices & Deprecations

This release requires PHP 8.1 or later.

Starting this release, the deployment process requires `npm` to pull down
JavaScript code that is served up to the browser and bundle it as part of
deployment. See [INSTALL.md](INSTALL.md).

Some translated strings were moved from PHP files to JS files. If you have
site translations enabled, after upgrading the code you must regenerate the
PO template and refresh the PO file for each enabled language to have the
strings be picked up by the JS code.

### Changes

* Authors code (`tools/authors/*`) has been removed. Biographies will be
  inserted directly into the project comments with the included upgrade script
  (cpeel)
* Updated minimum browser versions, see [INSTALL.md](INSTALL.md).

## R202503
Scripts supporting this upgrade are in `SETUP/upgrade/22`

### Notices & Deprecations

This is the last release to support PHP 7.4. Future releases will only
support PHP 8.1 and later. Note: This deprecation was originally announced
for R202409 but was moved to this release instead.

This is the last release to support the Authors code (`tools/authors/*`).

The `array_get()` function has been deprecated and will be removed in the next
release. Use the [null coalescing operator](https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.coalesce)
instead.

### Changes

* WikiHiero support has been removed (cpeel)
* New APIs (70ray, cpeel) -- see [`dp-openapi.yaml`](../api/dp-openapi.yaml)
  for complete spec
  * `v1/documents`
  * `v1/documents/:document`
  * `v1/dictionaries`
  * `v1/projects/:projectid/pages/:pagename/reportbad`
  * `v1/projects/:projectid/pages/:pagename/wordcheck`
  * `v1/projects/:projectid/pickersets`
  * `v1/projects/:projectid/wordcheck`
  * `v1/storage/:storagekey` -- see also the [API Admin Guide](API.md)
* Check for corrupt images in Project Quick Check and during project load (cpeel)
* Replaced all raw shell calls with Symfony's Process class (mrducky4, cpeel)
* Work to remove inline JavaScript to `.js` files (70ray)
* Extensive typing added to functions and classes (jchaffraix)
* JavaScript style formatter updates (chrismiceli)
* Composer dependencies updated and Symfony polyfills up to 8.4 added (cpeel)

## R202409
Scripts supporting this upgrade are in `SETUP/upgrade/21`

### Notices & Deprecations

This is the last release to support the Hieroglyphs tool. WikiHiero which backs
the tool has been unsupported for 20+ years and was written for PHP 4.3.3.

Note: A prior changelog version said this was the last release to support PHP 7.4.
That deprecation has been moved to R202503 release instead.

### Changes

* Updated minimum middleware to MySQL 8.0 (cpeel)
* The deprecated globals mentioned in the last release notes were removed (cpeel)
* Most `faq/*` content has been deleted (cpeel)
* A `metadata.json` file has replaced `dc.xml` in the project directory (cpeel)
* New API endpoints for proofreading (70ray)
* Cronjobs now run through common interface with SA UI to show job status (cpeel)
* Typing added to numerous functions and classes (bpfoley, jchaffraix)
* XML feed types removed and feed backend improved (mrducky4)
* Navigation bar updates for smaller screens (cpeel)
* New site search page (cpeel)
* `past_tallies` is now a sparse table (cpeel)

## R202403
Scripts supporting this upgrade are in `SETUP/upgrade/20`

### Notices & Deprecations
This is the last release to explicitly support MySQL 5.7. Future releases may
continue to work with this version but only 8.x will be tested in the future.

This is the last release to include most `faq/*` content. These documents have
not been maintained for quite some time as pgdp.net has moved them to their wiki.

This is the last release where `dc.xml` files will be generated for each
project and the MARC records retained in the database.

The following globals are deprecated and will be removed in the next release:
* `$Activity_for_id_`
* `$Pool_for_id_`
* `$Stage_for_id_`
* `$Round_for_round_id_`
* `$Round_for_round_number_`
* `$Round_for_project_state_`
* `$Round_for_page_state_`
* `$PAGE_STATES_IN_ORDER`
* `$project_state_medium_label_`
* `$project_state_long_label_`
* `$project_state_forum_`
* `$project_state_phase_`
* `$project_states_for_star_metal_`
* `PROJECT_STATES_IN_ORDER`
* `$project_status_descriptors`

The following constant is deprecated and will be removed in the next release:
* `MAX_NUM_PAGE_EDITING_ROUNDS`

### Changes
* Session management by cookie has been removed in lieu of PHP-based sessions
  which have been the DP code default since before 2004 (cpeel)
* New My Suggestions page (cpeel)
* New `Cyrillic` character suite (srjfoo)
* Smart quotes converted to straight quotes at point of entry
  in the proofreading interface (chrismiceli)
* Highlight non-Latin Unicode scripts in WordCheck (cpeel)
* SAs allowed to delete illustrations at any stage (srjfoo)
* Improvements for file uploads and validation (70ray, cpeel)
* Smoothreading notification improvements (70ray)
* Format Preview updates (70ray)
* Updated French & German message localization files (srjfoo via olive & mcbax)
* Several new APIs (bpfoley) -- see [`dp-openapi.yaml`](../api/dp-openapi.yaml)
  for complete spec
  * `v1/projects/:projectid/transitions`
  * `v1/queues`
  * `v1/queues/:queueid`
  * `v1/queues/:queueid/stats`
  * `v1/queues/:queueid/projects`
  * `v1/stats/site/projects/stages`
  * `v1/stats/site/projects/states`
* Remove moment.js, and thus CloudFlare CDN, dependency (chrismiceli)
* Release queue page & backend improvements (cpeel, jmdyck)
* Fix inconsistencies in bronze/silver/gold categories (windymilla)
* Page load & SQL performance improvements (cpeel)
* Numerous code quality and robustness improvements (bpfoley)

## R202309
Scripts supporting this upgrade are in `SETUP/upgrade/19`

**This is the last release to include support for the original DP session
management with cookies. Future releases will only support PHP sessions
(the default since before 2004).**

* Emails are now sent with PHPMailer enabling HTML emails (windymilla)
  * Configure with `_PHPMAILER_SMTP_CONFIG` in `configuration.sh`
* Updates to the forum abstraction code (cpeel)
  * _The following variables in `configuration.sh` have changed._
    * `_FORUMS_DIR` is now `_PHPBB_DIR`
    * `_FORUMS_URL` is now `_PHPBB_URL`
* API responses now include error numbers (70ray)
* Some accented vowels added to Basic Latin character suite (srjfoo)
* Event notifications are now sent in receiver's language (windymilla)
* Numerous improvements for PP and PPV (windymilla)
* Reduce jQuery dependency (chrismiceli)
* Numerous bugfixes and improvements (windymilla, 70ray)

## R202303
No scripts are required for this upgrade.

* Support PHP 8.x (cpeel, srjfoo)
* Format Preview updates (70ray)
* Check for small images on project load and in Project Quick Check (chrismiceli)
* Revision of PPV form and calculation algorithm (windymilla)
* Small adjustments to manual project transition edgecases (srjfoo)

## R202209
Scripts supporting this upgrade are in `SETUP/upgrade/18`

* Several DP API updates (cpeel)
  * Create & edit projects
  * Add & remove project holds
  * Properly return numeric values as strings for string fields
* New `Math symbols` character suite (bunny-crunch, okrick, srjfoo, windymilla)
* Format Preview updates (70ray)
* Upgrade node dependencies (chrismiceli)
* Upgrade composer dependencies, including portable-utf8 (cpeel)
* Removed the incomplete Metadata & Correction features (cpeel)

## R202202
Scripts supporting this upgrade are in `SETUP/upgrade/17`

**This is the last release to include support for the incomplete Metadata
and Corrections features.**

* Improved security for session cookies (chrismiceli)
* Graphs now rendered via client-side Javascript as themed SVGs (chrismiceli)
* Removed jpgraph and GD dependencies (chrismiceli)
* Image Browser improvements (70ray)
* Format Preview footnote improvements (70ray)
* DP API additions: character suites, special days, image sources (cpeel)
* Minor code speed optimizations (cpeel)
* Updated minimum browser versions, see [INSTALL.md](INSTALL.md).

## R202109
Scripts supporting this upgrade are in `SETUP/upgrade/16`

**This is the last release to support jpgraph. Future releases will use
JS-generated SVG graphs.**

* Updated minimum middleware to PHP 7.4 and MySQL 5.7 (cpeel)
* Moved to use Composer for dependency packaging (cpeel)
* DP API is now enabled by default as it is used by JS for the UI (cpeel)
* New standard Image Widget used in Page Browser and proofreading interfaces (70ray)
* User-provided HTML is sanitized before output (cpeel)
* Improved cookie security and centralized cookie management (chrismiceli)
* Renamed the 'Science Fiction' genre to 'Science Fiction & Fantasy' (srjfoo)
  * There is no upgrade script for the `queue_defns` table; since
    release queues do not come pre-defined, updating those entries
    should be done manually by those who have genre queues enabled.
* Links to Project Gutenberg updated to use HTTPS (srjfoo)
* Code style standardization via PHP-CS-Fixer and GitHub linting (cpeel)


## R202102
Scripts supporting this upgrade are in `SETUP/upgrade/15`

**This is the last release to support PHP versions < 7.4 and
MySQL versions < 5.7.**

* Support MySQL 8.0, PHP 7.2, and PHP 7.4 (chrismiceli, cpeel, bpfoley)
* New RESTful API - see [API.md](API.md) (cpeel, bpfoley)
* New user workflow improvements allows P1 to have a quiz requirement (cpeel)
* Project comments can be either HTML or Markdown (chrismiceli)
* Format Preview enhancements, including LaTeX math preview (70ray)
* New dark theme: Charcoal (srjfoo)
* Special Days projects identified by emojis in round listings (cpeel)
* PPers can return a project back to the PPVer who returned it to them (70ray)
* New character suites (bunny-crunch, srjfoo)
  * `Semitic and Indic transcriptions`
  * `Symbols collection`
* Task details and comments are now in Markdown (chrismiceli)
* Users can be members of up to 6 teams (cpeel)
* CSS updates and standardization across the codebase (cpeel, srjfoo)
* Continued work to ensure proper SQL & HTML escaping (chrismiceli, bpfoley)
* CI/CD & linting improvements (chrismiceli, bpfoley, cpeel)
* Removed TEI support (cpeel)


## R202009
Scripts supporting this upgrade are in `SETUP/upgrade/14`

**This is the last version to include support for Internet Explorer 11.**

* **Security fixes** - this release fixes potential SQL injection, XSS, and
  CSRF vulnerabilities (cpeel, chrismiceli, independent security researcher
  Dipu1A)
* **Unicode support** - the site is now fully UTF-8 compatible. See
  [UNICODE.md](UNICODE.md) for more information on what this means for you.
  (cpeel, 70ray, chrismiceli, srjfoo, numerous others)
* Support phpBB 3.3 (cpeel)
* Support jpgraph version 4.3.x (cpeel)
* Supported browsers now listed in [INSTALL.md](INSTALL.md)
* More pages require authentication, including the quizzes and stats (cpeel)
* Replaced DPCustomMono2 with DP Sans Mono (lvl)
* Optional daily page limits (jmdyck)
* File upload abstraction enables large file uploads for PP and SR (70ray)
* Many SR improvements (70ray)
* Search & Replace tooling updates (chrismiceli)
* Access change callbacks for activities allows emails to be sent when
  access to an activity is granted or revoked (cpeel)
* WordCheck status added to diff page and project pages (mlazaric)
* Redesigned display images interface (70ray)
* Global news items (chrismiceli)
* CSS updates for improved mobile layout (cpeel)
* SEO improvements (cpeel)
  * dynamic sitemap added
  * LD+JSON metadata added to project pages
* Updated French message localization files (srjfoo via Olive)
* DB schema documentation (mlazaric)
* Improved parameter validation and SQL error logging (cpeel, chrismiceli)


## R202002
Scripts supporting this upgrade are in `SETUP/upgrade/13`

* Updated minimum PHP version to 7.0 (cpeel)
* Create non-project database tables using default engine; for
  MySQL 5.5 and higher that is InnoDB. Project tables are still created
  using the MyISAM engine (cpeel)
* Smooth Reading posting and download enhancements (70ray)
* More flexible Smooth Reading deadline extensions (70ray)
* My Projects page redesigned (cpeel)
* New character picker with MRU menu (70ray)
* Added DejaVu Sans Mono as a web font and redesigned font selection
  in user preferences (cpeel)
* Various updates and improvements to Format Preview (70ray)
* Updates to Post-Processing reminder emails and new navbar
  notification (cpeel)
* Various Task Center improvements (mlazaric, cpeel)


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
* Moved to mysqli extension (cpeel)
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
* New round and related scripts (jmdyck)
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
