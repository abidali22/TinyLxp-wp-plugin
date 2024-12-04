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

function get_all_courses_for_enrollment() {
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => TL_COURSE_CPT,
        'orderby'        => 'meta_value_num',
        'order' => 'asc'
    );
    $query = new WP_Query($args);
    ob_start();
    // Check if there are any posts to display
    if ($query->have_posts()) {
        echo '<div class="recent-posts-custom-layout">';
        while ($query->have_posts()) {
            $query->the_post();
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_path = get_attached_file($thumbnail_id);
            echo '<div class="post-item">';
            if (has_post_thumbnail() && file_exists($thumbnail_path)) {
                echo '<div class="post-thumbnail">';
                echo '<a href="../selected-course?'.get_post()->post_name.'">';
                echo get_the_post_thumbnail(get_the_ID(), "medium", array( 'class' => 'rounded' )); // Display the post thumbnail
                echo '</a>';
                echo '</div>';
            } else {
                $treks_src = content_url().'/plugins/TinyLxp-wp-plugin/lms/templates/tinyLxpTheme/treks-src/';
                echo '<div class="post-thumbnail">';
                echo '<a href="../selected-course?'.get_post()->post_name.'">';
                echo '<img width="300" height="180" style="height:313px" src="'.$treks_src.'/assets/img/tr_main.jpg" class="rounded wp-post-image" />';
                echo '</a>';
                echo '</div>';
            }
            echo '<div class="post-content">';
            echo '<h2 class="post-title"><a href="../selected-course?'.get_post()->post_name.'">' . get_the_title() . '</a></h2>';
            echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
            echo '</div>'; // Close post-content
            echo '</div>'; // Close post-item
        }
        echo '</div>'; // Close recent-posts-custom-layout
    } else {
        echo '<p>No posts found.</p>';
    }
    // Restore original post data
    wp_reset_postdata();
    // Return the content
    return ob_get_clean();
}
add_shortcode('enrolment_courses', 'get_all_courses_for_enrollment');

function get_selected_course_data() {
    function get_custom_query_string() {
        // Get the current URL
        $url = home_url( add_query_arg( array() ) );
        
        // Parse the URL and extract the query string
        $query_string = parse_url($url, PHP_URL_QUERY);
        
        // Check if the query string exists and matches the desired pattern
        if ($query_string) {
            return $query_string;
        } else {
            return null;
        }
    }
    $currentPostName = get_custom_query_string();

    if ($currentPostName != null) {
        $args = array(
            'name'        => sanitize_title($currentPostName),
            'post_type'   => TL_COURSE_CPT,
            'post_status' => 'publish',
            'posts_per_page' => 1
        );
        $query = new WP_Query($args);
    }

    if (isset($query) && $query->have_posts()) {
        // Output the HTML with PHP variables
        echo '<div class="post-container">';
        echo '<div class="post-thumbnail">';
        if (has_post_thumbnail($query->post->ID)) {
            echo '<img src="' . get_the_post_thumbnail_url($query->post->ID) . '" alt="Post Thumbnail">';
        } else {
            $treks_src = content_url().'/plugins/TinyLxp-wp-plugin/lms/templates/tinyLxpTheme/treks-src/';
            echo '<img width="300" height="180" style="height:313px" src="'.$treks_src.'/assets/img/tr_main.jpg" class="rounded wp-post-image" />';
        }
        echo '</div>';
        echo '<div class="post-content">';
        echo '<h2 class="post-title">' . esc_html($query->post->post_title) . '</h2>';
        echo '<p class="post-description">' . esc_html(wp_trim_words($query->post->post_content, 40, '...')) . '</p>';
        echo '<p></p>';
        echo '<p></p>';
        echo '<p></p></div>';
        echo '<button id="loginButton" style="margin-top:25%" class="glow-on-hover" type="button">Enroll Now</button>';
        echo '</div>';
    } else {
        echo '<p>No course found.</p>';
    }

}
add_shortcode('selected_course', 'get_selected_course_data');

function get_activity() {
    $url = home_url( add_query_arg( array() ) );    
    $url_components = parse_url($url);
    // Split path into segments
    if (isset($url_components['path'])) {
        $path_segments = explode('/', trim($url_components['path'], '/'));
    }
    $args = array(
        'name'        => $path_segments[3],
        'post_type'   => LP_LESSON_CPT,
        'post_status' => 'publish',
        'numberposts' => 1
    );
    $posts = get_posts($args);
    $post = $posts[0];
    $_GET['post'] = $post->ID;
    $content = get_post_meta($post->ID);
    $attrId =  isset($content['lti_post_attr_id'][0]) ? $content['lti_post_attr_id'][0] : "";
    $title =  isset($content['lti_content_title'][0]) ? $content['lti_content_title'][0] : "";
    $toolCode =  isset($content['lti_tool_code'][0]) ? $content['lti_tool_code'][0] : "";
    $customAttr =  isset($content['lti_custom_attr'][0]) ? $content['lti_custom_attr'][0] : "";
    $toolUrl =  isset($content['lti_tool_url'][0]) ? $content['lti_tool_url'][0] : "";
    $plugin_name = Tiny_LXP_Platform::get_plugin_name();
    $content = '<p>' . $post->post_content . '</p>';
    if ($attrId) {
        $content .= '<p> [' . $plugin_name . ' tool=' . $toolCode . ' id=' . $attrId . ' title=\"' . $title . '\" url=' . $toolUrl . ' custom=' . $customAttr . ']' . "" . '[/' . $plugin_name . ']  </p>';
    }
    
    $queryParam = '';
    echo '<iframe style="border: none;width: 100%;height: 706px;" class="" src="'.site_url().'?lti-platform&post='.$post->ID.'&id='.$attrId.$queryParam.'" allowfullscreen></iframe>';
}
add_shortcode('selected_activity', 'get_activity');