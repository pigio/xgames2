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
 * Display profile for a particular user
 *
 * @package core_user
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../config.php");
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/badgeslib.php');

$id             = optional_param('id', 0, PARAM_INT); // User id.
$courseid       = optional_param('course', SITEID, PARAM_INT); // course id (defaults to Site).
$showallcourses = optional_param('showallcourses', 0, PARAM_INT);



// See your own profile by default.
if (empty($id)) {
    require_login();
    $id = $USER->id;
}

if ($courseid == SITEID) {   // Since Moodle 2.0 all site-level profiles are shown by profile.php.
    redirect($CFG->wwwroot.'/leaderboard/profile.php?id='.$id);  // Immediate redirect.
}

$PAGE->set_url('/leaderboard/view.php', array('id' => $id, 'course' => $courseid));

$user = $DB->get_record('user', array('id' => $id), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$currentuser = ($user->id == $USER->id);

$systemcontext = context_system::instance();
$coursecontext = context_course::instance($course->id);
$usercontext   = context_user::instance($user->id, IGNORE_MISSING);

// Check we are not trying to view guest's profile.
if (isguestuser($user)) {
    // Can not view profile of guest - thre is nothing to see there.
    print_error('invaliduserid');
}

$PAGE->set_context($coursecontext);

if (!empty($CFG->forceloginforprofiles)) {
    require_login(); // We can not log in to course due to the parent hack below.

    // Guests do not have permissions to view anyone's profile if forceloginforprofiles is set.
    if (isguestuser()) {
        echo $OUTPUT->header();
        echo $OUTPUT->confirm(get_string('guestcantaccessprofiles', 'error'),
                              get_login_url(),
                              $CFG->wwwroot);
        echo $OUTPUT->footer();
        die;
    }
}

$PAGE->set_course($course);
$PAGE->set_pagetype('course-view-' . $course->format);  // To get the blocks exactly like the course.
$PAGE->add_body_class('path-leaderboard');                     // So we can style it independently.
$PAGE->set_other_editing_capability('moodle/course:manageactivities');

// Set the Moodle docs path explicitly because the default behaviour
// of inhereting the pagetype will lead to an incorrect docs location.
$PAGE->set_docs_path('user/profile');

$isparent = false;

if (!$currentuser and !$user->deleted
  and $DB->record_exists('role_assignments', array('userid' => $USER->id, 'contextid' => $usercontext->id))
  and has_capability('moodle/user:viewdetails', $usercontext)) {
    // TODO: very ugly hack - do not force "parents" to enrol into course their child is enrolled in,
    //       this way they may access the profile where they get overview of grades and child activity in course,
    //       please note this is just a guess!
    require_login();
    $isparent = true;
    $PAGE->navigation->set_userid_for_parent_checks($id);
} else {
    // Normal course.
    require_login($course);
    // What to do with users temporary accessing this course? should they see the details?
}

$strpersonalprofile = get_string('personalprofile');
$strparticipants = get_string("participants");
$struser = get_string("user");

$fullname = fullname($user, has_capability('moodle/site:viewfullnames', $coursecontext));



$PAGE->set_title("$course->fullname: $strpersonalprofile: $fullname");
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('standard');

// Locate the users settings in the settings navigation and force it open.
// This MUST be done after we've set up the page as it is going to cause theme and output to initialise.
if (!$currentuser) {
    $PAGE->navigation->extend_for_user($user);
    if ($node = $PAGE->settingsnav->get('userviewingsettings'.$user->id)) {
        $node->forceopen = true;
    }
} else if ($node = $PAGE->settingsnav->get('usercurrentsettings', navigation_node::TYPE_CONTAINER)) {
    $node->forceopen = true;
}
if ($node = $PAGE->settingsnav->get('courseadmin')) {
    $node->forceopen = false;
}

echo $OUTPUT->header();

echo '<div class="userprofile">';
$headerinfo = array('heading' => fullname($user), 'user' => $user, 'usercontext' => $usercontext);
echo $OUTPUT->context_header($headerinfo, 2);

if ($user->deleted) {
    echo $OUTPUT->heading(get_string('userdeleted'));
    if (!has_capability('moodle/user:update', $coursecontext)) {
        echo $OUTPUT->footer();
        die;
    }
}



// Render custom blocks.
//$renderer = $PAGE->get_renderer('leaderboard', 'myprofile');
//$tree = leaderboard\output\myprofile\manager::build_tree($user, $currentuser, $course);
//echo $renderer->render($tree);

echo '</div>';  // Userprofile class.
echo $OUTPUT->footer();
