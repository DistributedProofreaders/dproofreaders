<?php
include_once("ApiRouter.inc");

// DP API v1
include_once("v1_projects.inc");

$router = ApiRouter::get_router();

// Add validators
$router->add_validator(":wordlist_type", "validate_wordlist");
$router->add_validator(":projectid", "validate_project");

// Add routes
$router->add_route("GET", "v1/projects", "api_v1_projects");
$router->add_route("GET", "v1/projects/:projectid", "api_v1_project");
$router->add_route("GET", "v1/projects/:projectid/wordlists/:wordlist_type", "api_v1_project_wordlists");
$router->add_route("PUT", "v1/projects/:projectid/wordlists/:wordlist_type", "api_v1_project_wordlists");
$router->add_route("GET", "v1/projects/difficulties", "api_v1_projects_difficulties");
$router->add_route("GET", "v1/projects/genres", "api_v1_projects_genres");
$router->add_route("GET", "v1/projects/languages", "api_v1_projects_languages");
$router->add_route("GET", "v1/projects/states", "api_v1_projects_states");

