<?php
/*
 * This is a wrapper function around code that defines the structure
 * of the site -- stages, rounds, pools, and their dependencies.
 *
 * The order of these includes are important as there are implicit
 * dependencies between them.
 */

// Project states
require_once($relPath."project_states.inc");

// Stages, Rounds, and Pools
require_once($relPath."stages.inc");

// Quizzes
require_once($relPath."quizzes.inc");
