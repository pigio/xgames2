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
 * This page lists public api for tool_monitor plugin.
 *
 * @package    tool_monitor
 * @copyright  2014 onwards Ankit Agarwal <ankit.agrr@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;



/**
 * This function extends the navigation with the tool items
 *
 * @param navigation_node $navigation The navigation node to extend
 * @param stdClass        $course     The course to object for the tool
 * @param context         $context    The context of the course
 */
function local_leaderboard_extend_navigation_frontpage($navigation) {

		  global $USER;
          global $PAGE;


$previewnode = $PAGE->navigation->add(get_string('nav_leaderboard'), new moodle_url('/local/leaderboard/leaderboard.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add(get_string('nav_leaderboard'), new moodle_url('/local/leaderboard/leaderboard.php'));
$thingnode->make_active();

   
}

function local_leaderboard_extend_navigation(global_navigation $navigation)
{
    $main_node = $navigation->add(get_string('pluginname', 'local_leaderboard'), new moodle_url('/local/leaderboard/leaderboard.php'), 0, 'nav_leaderboard', 'local_leaderboard',  new \pix_icon('t/award',''));
    $main_node->nodetype = 1;
    $main_node->collapse = false;
    $main_node->forceopen = true;
    $main_node->isexpandable = false;
    $main_node->showinflatnavigation = true;
 
}

?>