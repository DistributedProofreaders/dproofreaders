<?php
include_once("ApiRouter.inc");

// DP API v1
include_once("v1_validators.inc");
include_once("v1_projects.inc");
include_once("v1_queues.inc");
include_once("v1_stats.inc");
include_once("v1_docs.inc");
include_once("v1_storage.inc");

$router = ApiRouter::get_router();

// Add validators
$router->add_validator(":wordlist_type", "validate_wordlist");
$router->add_validator(":projectid", "validate_project");
$router->add_validator(":stageid", "validate_stage");
$router->add_validator(":roundid", "validate_round");
$router->add_validator(":pagename", "validate_page_name");
$router->add_validator(":pageroundid", "validate_page_round");
$router->add_validator(":queueid", "validate_release_queue");
$router->add_validator(":document", "validate_document");
$router->add_validator(":storagekey", "validate_storage_key");

// Add routes
$router->add_route("GET", "v1/documents", "api_v1_documents");
$router->add_route("GET", "v1/documents/:document", "api_v1_document");
$router->add_route("GET", "v1/dictionaries", "api_v1_dictionaries");

$router->add_route("GET", "v1/projects", "api_v1_projects");
$router->add_route("POST", "v1/projects", "api_v1_project");
$router->add_route("GET", "v1/projects/difficulties", "api_v1_projects_difficulties");
$router->add_route("GET", "v1/projects/genres", "api_v1_projects_genres");
$router->add_route("GET", "v1/projects/languages", "api_v1_projects_languages");
$router->add_route("GET", "v1/projects/states", "api_v1_projects_states");
$router->add_route("GET", "v1/projects/pagerounds", "api_v1_projects_pagerounds");
$router->add_route("GET", "v1/projects/charsuites", "api_v1_projects_charsuites");
$router->add_route("GET", "v1/projects/specialdays", "api_v1_projects_specialdays");
$router->add_route("GET", "v1/projects/imagesources", "api_v1_projects_imagesources");
$router->add_route("GET", "v1/projects/holdstates", "api_v1_projects_holdstates");
$router->add_route("GET", "v1/projects/:projectid", "api_v1_project");
$router->add_route("PUT", "v1/projects/:projectid", "api_v1_project");
$router->add_route("GET", "v1/projects/:projectid/artifacts/:stageid", "api_v1_project_artifacts");
$router->add_route("PUT", "v1/projects/:projectid/checkout", "api_v1_project_checkout");
$router->add_route("GET", "v1/projects/:projectid/holdstates", "api_v1_project_holdstates");
$router->add_route("PUT", "v1/projects/:projectid/holdstates", "api_v1_project_holdstates");
$router->add_route("GET", "v1/projects/:projectid/pagedetails", "api_v1_project_pagedetails");
$router->add_route("GET", "v1/projects/:projectid/pages", "api_v1_project_pages");
$router->add_route("PUT", "v1/projects/:projectid/pages/:pagename", "api_v1_project_page");
$router->add_route("GET", "v1/projects/:projectid/pages/:pagename", "api_v1_project_page");
$router->add_route("PUT", "v1/projects/:projectid/pages/:pagename/reportbad", "api_v1_project_page_report_bad");
$router->add_route("PUT", "v1/projects/:projectid/pages/:pagename/wordcheck", "api_v1_project_page_wordcheck");
$router->add_route("GET", "v1/projects/:projectid/pages/:pagename/pagerounds/:pageroundid", "api_v1_project_page_round");
$router->add_route("GET", "v1/projects/:projectid/pickersets", "api_v1_project_pickersets");
$router->add_route("GET", "v1/projects/:projectid/transitions", "api_v1_project_transitions");
$router->add_route("PUT", "v1/projects/:projectid/validatetext", "api_v1_project_validatetext");
$router->add_route("PUT", "v1/projects/:projectid/wordcheck", "api_v1_project_wordcheck");
$router->add_route("GET", "v1/projects/:projectid/wordlists/:wordlist_type", "api_v1_project_wordlists");
$router->add_route("PUT", "v1/projects/:projectid/wordlists/:wordlist_type", "api_v1_project_wordlists");

$router->add_route("GET", "v1/queues", "api_v1_queues");
$router->add_route("GET", "v1/queues/:queueid", "api_v1_queue");
$router->add_route("GET", "v1/queues/:queueid/stats", "api_v1_queue_stats");
$router->add_route("GET", "v1/queues/:queueid/projects", "api_v1_queue_projects");

$router->add_route("GET", "v1/stats/site", "api_v1_stats_site");
$router->add_route("GET", "v1/stats/site/projects/stages", "api_v1_stats_site_projects_stages");
$router->add_route("GET", "v1/stats/site/projects/states", "api_v1_stats_site_projects_states");
$router->add_route("GET", "v1/stats/site/rounds", "api_v1_stats_site_rounds");
$router->add_route("GET", "v1/stats/site/rounds/:roundid", "api_v1_stats_site_round");

$router->add_route("GET", "v1/storage/:storagekey", "api_v1_storage");
$router->add_route("PUT", "v1/storage/:storagekey", "api_v1_storage");
$router->add_route("DELETE", "v1/storage/:storagekey", "api_v1_storage_delete");
