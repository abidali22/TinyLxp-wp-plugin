<?php
  global $treks_src;
  // filter $assignments based on "Completed" status and not having "To Do", "In Progress" statuses and count should be equal to total students
  $assignments = array_filter($assignments, function($assignment) {
    $student_stats = lxp_assignment_stats($assignment->ID);
    $statuses = array("Completed");
    $students_completed = array_filter($student_stats, function($studentStat) use ($statuses) {
      return in_array($studentStat["status"], $statuses);
    });
    return count($students_completed) > 0 && count($students_completed) === count($student_stats);
    //return count($students_completed) > 0;
  });
?>
<!--  table -->
<div id="completed-tab-content" class="pending-assignments-table tab-pane fade" role="tabpanel">
  <table>
    <thead>
      <tr>
        <th>Class</th>
        <th>Course</th>
        <th>Lesson</th>
        <th>Due Date</th>
        <th>Student Progress</th>
        <th>Students Submitted</th>
        <th>Students Graded</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        foreach ($assignments as $assignment) { 
          $course = get_post(get_post_meta($assignment->ID, 'course_id', true));
          if (is_object($course)) {
          $calendar_selection_info = json_decode(get_post_meta($assignment->ID, 'calendar_selection_info', true));
          $start = '';
          if (!is_null($calendar_selection_info) && property_exists($calendar_selection_info, 'start') && gettype($calendar_selection_info->start) === 'string') {
            $start = $calendar_selection_info->start;
          } elseif (!is_null($calendar_selection_info) && property_exists($calendar_selection_info, 'start') && gettype($calendar_selection_info->start) === 'object') {
            $start = $calendar_selection_info->start->date;
          }

          $end = '';
          if (!is_null($calendar_selection_info) && property_exists($calendar_selection_info, 'end') && gettype($calendar_selection_info->end) === 'string') {
            $end = $calendar_selection_info->end;
          } elseif (!is_null($calendar_selection_info) && property_exists($calendar_selection_info, 'end') && gettype($calendar_selection_info->end) === 'object') {
            $end = $calendar_selection_info->end->date;
          }

          $class_post = get_post(get_post_meta($assignment->ID, 'class_id', true));
          $lxp_lesson_post = get_post(get_post_meta($assignment->ID, 'lxp_lesson_id', true));
          $section_name = $wpdb->get_var($wpdb->prepare(
              "SELECT s.section_name
              FROM {$wpdb->prefix}learnpress_sections s
              INNER JOIN {$wpdb->prefix}learnpress_section_items si ON s.section_id = si.section_id
              WHERE si.item_id = %d",
              $lxp_lesson_post->ID
          ));
          
          $student_stats = lxp_assignment_stats($assignment->ID);
          $statuses = array("To Do", "In Progress");
          $students_in_progress = array_filter($student_stats, function($studentStat) use ($statuses) {
            return in_array($studentStat["status"], $statuses);
          });

          $statuses = array("Completed");
          $students_graded = 0;
          $students_completed = array_filter($student_stats, function($studentStat) use ($statuses, $assignment, &$students_graded) {
            $ok = false;
            $ok = in_array($studentStat["status"], $statuses);
            if ($ok) {
              $assignment_submission_item = lxp_get_assignment_submissions($assignment->ID, $studentStat["ID"]);
              if (count($assignment_submission_item) > 0 && get_post_meta($assignment_submission_item['ID'], 'mark_as_graded', true) === 'true') {
                $students_graded++;
                $ok = false;
              }
            }
            return $ok;
          });

          if ( $students_graded === count($student_stats) ) { 
      ?>
        <tr>
          <td><?= $class_post->post_title; ?></td>
          <td>
            <?php 
              $title = str_replace("'", "`", $course->post_title);
              $lxp_lesson_post->post_title = str_replace('"', "`", $lxp_lesson_post->post_title);
              echo $title; 
              $thumbnail = has_post_thumbnail( $course->ID ) ? get_the_post_thumbnail_url($course->ID) : $treks_src.'/assets/img/tr_main.jpg';
            ?>
          </td>
          <td>
            <div class="assignments-table-cs-td-poly">
              <div class="polygon-shap">
                <span>L</span>
              </div>
              <div>
                <span><?= $lxp_lesson_post->post_title; ?></span>
              </div>
            </div>
          </td>
          <td>
            <?php
              $start_date = get_post_meta($assignment->ID, "start_date", true);
              $start_date = date("M d, Y", strtotime($start_date));
              echo $start_date;
            ?>
          </td>
          <td>
            <div class="student-stats-link"><a href="#" onclick="fetch_assignment_stats(<?= $assignment->ID; ?>, '<?= $thumbnail; ?>', '<?= $course_title; ?>', '<?= $section_name; ?>', '<?= $lxp_lesson_post->post_title; ?>', ['To Do', 'In Progress'], '<?= $start; ?>', '<?= $end; ?>')"><?= count($students_in_progress); ?>/<?= count($student_stats); ?></a></div>
          </td>
          <td>
            <div class="student-stats-link"><a href="#" onclick="fetch_assignment_stats(<?= $assignment->ID; ?>, '<?= $thumbnail; ?>', '<?= $course_title; ?>', '<?= $section_name; ?>', '<?= $lxp_lesson_post->post_title; ?>', ['Completed'], '<?= $start; ?>', '<?= $end; ?>')"><?= count($students_completed); ?>/<?= count($student_stats); ?></a></div>
          </td>
          <td>
            <div class="student-stats-link"><a href="#" onclick="fetch_assignment_stats(<?= $assignment->ID; ?>, '<?= $thumbnail; ?>', '<?= $course_title; ?>', '<?= $section_name; ?>', '<?= $lxp_lesson_post->post_title; ?>', ['Graded'], '<?= $start; ?>', '<?= $end; ?>')"><?= $students_graded; ?>/<?= count($student_stats); ?></a></div>
          </td>
        </tr>  
      <?php } } } ?>
    </tbody>
  </table>
</div>