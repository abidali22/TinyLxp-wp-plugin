<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'tiny-lxp-resource/Activity.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/tl-constants.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-abstract-tl-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-course-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-lesson-post-type.php';
// require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-trek-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-tl-admin-menu.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-district-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-school-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-assignment-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-teacher-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-student-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-class-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-assignment-submission-post-type.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'lms/class-group-post-type.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

TL_Course_Post_Type::instance();
TL_Lesson_Post_Type::instance();
// TL_TREK_Post_Type::instance();
TL_District_Post_Type::instance();
TL_School_Post_Type::instance();
TL_Assingment_Post_Type::instance();
TL_Admin_Menu::instance();
TL_Teacher_Post_Type::instance();
TL_Student_Post_Type::instance();
TL_Class_Post_Type::instance();
TL_Group_Post_Type::instance();
TL_Assingment_Submission_Post_Type::instance();

function tinyLxp_page_templates($template) {
    // Check if the current page is a specific page
    require_once plugin_dir_path(dirname( __FILE__ )). '/lms/templates/tinyLxpTheme/lxp/functions.php';
    if (is_page('login')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-login.php';
    } elseif (is_page('dashboard')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-dashboard.php';
    } elseif (is_page('districts')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-districts.php';
    } elseif (is_page('schools')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-schools.php';
    } elseif (is_page('teachers')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-teachers.php';
    } elseif (is_page('students')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-students.php';
    } elseif (is_page('classes')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-classes.php';
    } elseif (is_page('groups')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-groups.php';
    } elseif (is_page('courses')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-courses.php';
    } elseif (is_page('assignments')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-assignments.php';
    } elseif (is_page('assignment')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-assignment.php';
    } elseif (is_page('calendar')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-calendar.php';
    } elseif (is_page('grades')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-grades.php';
    } elseif (is_page('grade-assignment')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-grade-assignment.php';
    } elseif (is_page('grade-summary')) {
        $template = plugin_dir_path(dirname( __FILE__ )).'/lms/templates/tinyLxpTheme/page-grade-summary.php';
    }

    return $template; // Return the original template if conditions are not met
}

// Hook your callback function to the page_template filter hook
add_filter('template_include', 'tinyLxp_page_templates', 99);

add_filter('show_admin_bar', '__return_false');