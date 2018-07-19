<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * YU Kaltura My Media Gallery main page
 *
 * @package    local_yumymedia
 * @copyright  (C) 2016-2017 Yamaguchi University <gh-cc@mlex.cc.yamaguchi-u.ac.jp>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once('lib.php');

defined('MOODLE_INTERNAL') || die();


header('Access-Control-Allow-Origin: *');

global $SESSION, $USER, $COURSE, $SITE;

$page = optional_param('page', 0, PARAM_INT);
$sort = optional_param('sort', 'recent', PARAM_TEXT);
$simplesearch = '';
$medias = 0;

$leaderboard = get_string('heading_leaderboard', 'local_leaderboard');
$PAGE->set_context(context_system::instance());
$header  = format_string($SITE->shortname).": $leaderboard";

$PAGE->set_url('/local/leaderboard/leaderboard.php');
$PAGE->set_course($SITE);

require_login();

$PAGE->set_pagetype('leaderboard-index');
$PAGE->set_pagelayout('standard');
$PAGE->set_title($header);
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/leaderboard/leaderboard.js'), true);
$PAGE->set_heading($header);
$PAGE->add_body_class('leaderboard-index');

$renderer = $PAGE->get_renderer('local_leaderboard');

$courseid = $COURSE->id;

echo $OUTPUT->header();


echo $renderer->my_leaderboard();
        

echo $OUTPUT->footer();
