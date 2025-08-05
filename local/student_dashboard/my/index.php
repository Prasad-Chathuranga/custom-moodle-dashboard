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
 * Custom student dashboard
 *
 * @package    local_student_dashboard
 * @copyright  2025 Custom Moodle Dashboard
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/lib/navigationlib.php');

// Include completion library if it exists
if (file_exists($CFG->dirroot . '/lib/completionlib.php')) {
    require_once($CFG->dirroot . '/lib/completionlib.php');
}

// Include course category library if it exists
if (file_exists($CFG->libdir . '/coursecatlib.php')) {
    require_once($CFG->libdir . '/coursecatlib.php');
}

require_login();

$PAGE->set_url('/my/index.php');
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_title(get_string('learningdashboard', 'local_student_dashboard'));
$PAGE->set_heading(get_string('learningdashboard', 'local_student_dashboard'));
$PAGE->set_pagetype('my-index');

// Check if user is a student
$context = context_system::instance();
$studentrole = $DB->get_record('role', array('shortname' => 'student'));
$isstudent = false;

if ($studentrole) {
    $isstudent = user_has_role_assignment($USER->id, $studentrole->id);
}

// If not a student, redirect to default dashboard
if (!$isstudent) {
    redirect($CFG->wwwroot . '/my/');
}

// Add CSS
$PAGE->requires->css('/local/student_dashboard/styles.css');

// Get student data
$enrolledcourses = enrol_get_my_courses('summary', 'fullname ASC');
$totalcourses = count($enrolledcourses);
$completedcourses = 0;
$incompletecourses = 0;

// Calculate completion stats
foreach ($enrolledcourses as $course) {
    // For now, we'll consider all courses as incomplete unless completion is specifically enabled
    // This can be enhanced later with proper completion tracking
    if (class_exists('completion_info')) {
        $completion = new completion_info($course);
        if ($completion->is_enabled()) {
            // Simple check - this can be enhanced based on specific Moodle version
            $params = array('userid' => $USER->id, 'course' => $course->id);
            $completiondata = $DB->get_record('course_completions', $params);
            if ($completiondata && $completiondata->timecompleted) {
                $completedcourses++;
            } else {
                $incompletecourses++;
            }
        } else {
            $incompletecourses++;
        }
    } else {
        // Fallback if completion tracking is not available
        $incompletecourses++;
    }
}

// Get recent activities
$recentitems = array();
if (!empty($enrolledcourses)) {
    $courseids = array_keys($enrolledcourses);
    $limit = 3;
    
    // Get recent forum posts, assignments, etc.
    foreach (array_slice($enrolledcourses, 0, $limit) as $course) {
        $coursecontext = context_course::instance($course->id);
        $recentitems[] = array(
            'title' => $course->fullname,
            'type' => get_string('course'),
            'url' => new moodle_url('/course/view.php', array('id' => $course->id))
        );
    }
}

echo $OUTPUT->header();
?>

<div class="student-dashboard">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="welcome-section">
            <div class="welcome-text">
                <h1><?php echo get_string('learningdashboard', 'local_student_dashboard'); ?></h1>
                <p><?php echo get_string('dashboardwelcome', 'local_student_dashboard', fullname($USER)); ?></p>
            </div>
            <div class="user-avatar">
                <?php echo $OUTPUT->user_picture($USER, array('size' => 100, 'class' => 'avatar-image')); ?>
            </div>
        </div>
        
        <!-- Information Bar -->
        <div class="info-bar">
            <div class="info-item">
                <i class="fa fa-info-circle"></i>
                <span><?php echo get_string('infobartext', 'local_student_dashboard'); ?></span>
                <button class="close-info">×</button>
            </div>
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="user-profile-section">
        <div class="profile-card">
            <div class="profile-avatar">
                <?php echo $OUTPUT->user_picture($USER, array('size' => 60)); ?>
            </div>
            <div class="profile-info">
                <h3><?php echo fullname($USER); ?></h3>
                <p><?php echo get_string('userrole', 'local_student_dashboard'); ?></p>
                <a href="<?php echo $CFG->wwwroot; ?>/user/profile.php" class="view-profile-link">
                    <?php echo get_string('viewprofile', 'local_student_dashboard'); ?> →
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-section">
            <!-- Badges -->
            <div class="stat-card badges-card">
                <h4><?php echo get_string('badges', 'local_student_dashboard'); ?></h4>
                <div class="badge-icons">
                    <div class="badge-item">
                        <div class="badge-icon badge-basic"></div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon badge-intermediate"></div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon badge-advanced"></div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon badge-expert"></div>
                    </div>
                </div>
            </div>

            <!-- Course Stats -->
            <div class="stat-card">
                <div class="stat-item">
                    <span class="stat-label"><?php echo get_string('enrolled', 'local_student_dashboard'); ?></span>
                    <span class="stat-value enrolled"><?php echo $totalcourses; ?></span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-item">
                    <span class="stat-label"><?php echo get_string('completed', 'local_student_dashboard'); ?></span>
                    <span class="stat-value completed"><?php echo $completedcourses; ?></span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-item">
                    <span class="stat-label"><?php echo get_string('incomplete', 'local_student_dashboard'); ?></span>
                    <span class="stat-value incomplete"><?php echo $incompletecourses; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recently Accessed Items -->
    <div class="recent-items-section">
        <h3><?php echo get_string('recentlyaccessed', 'local_student_dashboard'); ?></h3>
        
        <div class="recent-items-grid">
            <?php foreach ($enrolledcourses as $course): ?>
                <div class="recent-item-card">
                    <div class="item-icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <div class="item-content">
                        <h4><?php echo format_string($course->fullname); ?></h4>
                        <p><?php echo get_string('course'); ?></p>
                    </div>
                    <a href="<?php echo $CFG->wwwroot; ?>/course/view.php?id=<?php echo $course->id; ?>" 
                       class="item-link" aria-label="<?php echo get_string('gotocourse', 'local_student_dashboard', format_string($course->fullname)); ?>"></a>
                </div>
            <?php endforeach; ?>
            
            <?php if (empty($enrolledcourses)): ?>
                <div class="no-items">
                    <p><?php echo get_string('norecentitems', 'local_student_dashboard'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <a href="<?php echo $CFG->wwwroot; ?>/course/" class="view-more-btn">
            <?php echo get_string('viewmore', 'local_student_dashboard'); ?>
        </a>
    </div>
</div>

<script>
// Close info bar functionality
document.addEventListener('DOMContentLoaded', function() {
    const closeBtn = document.querySelector('.close-info');
    const infoBar = document.querySelector('.info-bar');
    
    if (closeBtn && infoBar) {
        closeBtn.addEventListener('click', function() {
            infoBar.style.display = 'none';
        });
    }
});
</script>

<?php
echo $OUTPUT->footer();
?>