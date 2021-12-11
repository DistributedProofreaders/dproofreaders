<?php
include_once("ApiRouter.inc");

// DP API v1
include_once("v1_validators.inc");
include_once("v1_projects.inc");
include_once("v1_stats.inc");

$router = ApiRouter::get_router();

// Add validators
$router->add_validator(":wordlist_type", "validate_wordlist");
$router->add_validator(":projectid", "validate_project");
$router->add_validator(":roundid", "validate_round");
$router->add_validator(":pagename", "validate_page_name");
$router->add_validator(":pageroundid", "validate_page_round");

// Add routes
$router->add_route("GET", "v1/projects", "api_v1_projects");
$router->add_route("GET", "v1/projects/:projectid", "api_v1_project");
$router->add_route("GET", "v1/projects/:projectid/wordlists/:wordlist_type", "api_v1_project_wordlists");
$router->add_route("PUT", "v1/projects/:projectid/wordlists/:wordlist_type", "api_v1_project_wordlists");
$router->add_route("GET", "v1/projects/difficulties", "api_v1_projects_difficulties");
$router->add_route("GET", "v1/projects/genres", "api_v1_projects_genres");
$router->add_route("GET", "v1/projects/languages", "api_v1_projects_languages");
$router->add_route("GET", "v1/projects/states", "api_v1_projects_states");
$router->add_route("GET", "v1/projects/pagerounds", "api_v1_projects_pagerounds");
$router->add_route("GET", "v1/projects/charsuites", "api_v1_projects_charsuites");
$router->add_route("GET", "v1/projects/:projectid/pages", "api_v1_project_pages");
$router->add_route("GET", "v1/projects/:projectid/pagedetails", "api_v1_project_pagedetails");
$router->add_route("GET", "v1/projects/:projectid/pages/:pagename/pagerounds/:pageroundid", "api_v1_project_page_round");

$router->add_route("GET", "v1/stats/site", "api_v1_stats_site");
$router->add_route("GET", "v1/stats/site/rounds", "api_v1_stats_site_rounds");
$router->add_route("GET", "v1/stats/site/rounds/:roundid", "api_v1_stats_site_round");
