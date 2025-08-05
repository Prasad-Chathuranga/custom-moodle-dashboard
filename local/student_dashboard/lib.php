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
 * Library functions for student dashboard
 *
 * @package    local_student_dashboard
 * @copyright  2025 Custom Moodle Dashboard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Hook to be called before my/index.php is loaded
 * This function will redirect students to the custom dashboard
 */
function local_student_dashboard_before_standard_html_head() {
    global $PAGE, $USER, $DB, $CFG, $SCRIPT;
    
    // Only redirect on the my/index.php page
    if ($SCRIPT !== '/my/index.php') {
        return;
    }
    
    // Make sure user is logged in
    if (!isloggedin() || isguestuser()) {
        return;
    }
    
    // Check if user is a student
    $studentrole = $DB->get_record('role', array('shortname' => 'student'));
    if (!$studentrole) {
        return;
    }
    
    $isstudent = user_has_role_assignment($USER->id, $studentrole->id);
    
    if ($isstudent) {
        // Redirect to custom student dashboard
        redirect($CFG->wwwroot . '/local/student_dashboard/my/index.php');
    }
}

/**
 * Add CSS to pages
 */
function local_student_dashboard_before_footer() {
    global $PAGE;
    
    if (strpos($PAGE->url->get_path(), '/local/student_dashboard/') !== false) {
        $PAGE->requires->css('/local/student_dashboard/styles.css');
    }
}