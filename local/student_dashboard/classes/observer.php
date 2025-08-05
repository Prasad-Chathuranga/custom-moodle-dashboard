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
 * Event observer for student dashboard
 *
 * @package    local_student_dashboard
 * @copyright  2025 Custom Moodle Dashboard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_student_dashboard;

defined('MOODLE_INTERNAL') || die();

/**
 * Event observer class
 */
class observer {
    
    /**
     * Observer for user login event
     *
     * @param \core\event\user_loggedin $event
     */
    public static function user_loggedin(\core\event\user_loggedin $event) {
        global $DB, $SESSION;
        
        // Get the user object
        $userid = $event->objectid;
        $user = $DB->get_record('user', array('id' => $userid));
        
        if (!$user) {
            return;
        }
        
        // Check if user is a student (has student role)
        $context = \context_system::instance();
        $studentrole = $DB->get_record('role', array('shortname' => 'student'));
        
        if ($studentrole) {
            $hasrole = user_has_role_assignment($userid, $studentrole->id);
            
            if ($hasrole) {
                // Set session variable to trigger redirect to custom dashboard
                $SESSION->student_dashboard_redirect = true;
            }
        }
    }
}