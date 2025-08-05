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
 * Custom my/index.php to redirect students to custom dashboard
 *
 * @package    local_student_dashboard
 * @copyright  2025 Custom Moodle Dashboard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');

// Include completion library if it exists
if (file_exists($CFG->dirroot . '/lib/completionlib.php')) {
    require_once($CFG->dirroot . '/lib/completionlib.php');
}

// Require login
require_login();

// Check if user is a student
$context = context_system::instance();
$studentrole = $DB->get_record('role', array('shortname' => 'student'));
$isstudent = false;

if ($studentrole) {
    $isstudent = user_has_role_assignment($USER->id, $studentrole->id);
}

// If user is a student, include our custom dashboard
if ($isstudent) {
    include(__DIR__ . '/../../my/index.php');
} else {
    // For non-students, include the original my/index.php
    include($CFG->dirroot . '/my/index.php');
}