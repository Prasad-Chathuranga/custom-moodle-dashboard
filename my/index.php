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
 * Custom my/index.php that shows student dashboard for students
 * and default dashboard for others
 *
 * @package    core
 * @copyright  2025 Custom Moodle Dashboard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../config.php');

// Require login
require_login();

// Check if user is a student and plugin exists
$studentrole = $DB->get_record('role', array('shortname' => 'student'));
$isstudent = false;
$pluginexists = file_exists($CFG->dirroot . '/local/student_dashboard/my/index.php');

if ($studentrole && $pluginexists) {
    $isstudent = user_has_role_assignment($USER->id, $studentrole->id);
}

// If user is a student and plugin exists, show custom dashboard
if ($isstudent && $pluginexists) {
    include($CFG->dirroot . '/local/student_dashboard/my/index.php');
} else {
    // Show default Moodle dashboard
    require_once($CFG->dirroot . '/lib/navigationlib.php');

    $hassiteconfig = has_capability('moodle/site:config', context_system::instance());
    $hasmanageblocks = has_capability('moodle/site:manageblocks', context_system::instance());

    $PAGE->set_url('/my/index.php');
    $PAGE->set_context(context_user::instance($USER->id));
    $PAGE->set_pagelayout('mydashboard');
    $PAGE->set_pagetype('my-index');
    $PAGE->blocks->add_region('content');
    $PAGE->set_subpage($USER->id);
    $PAGE->set_title(get_string('myhome'));
    $PAGE->set_heading(get_string('myhome'));

    if ($hasmanageblocks) {
        $PAGE->blocks->add_region('side-pre');
    }

    // Trigger dashboard has been viewed event.
    $eventparams = array('context' => $PAGE->context);
    $event = \core\event\dashboard_viewed::create($eventparams);
    $event->trigger();

    echo $OUTPUT->header();

    echo $OUTPUT->custom_block_region('content');

    echo $OUTPUT->footer();
}