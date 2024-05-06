<?php
  $student_post = lxp_get_student_post(get_current_user_id());
  $assignments = lxp_get_student_assignments($student_post->ID);
  $courseId = $args['course_id'];
  $assignments = array_filter($assignments, function ($assignment) use ($courseId) {
    return $assignment->course_id == $courseId;
  });
  $allSectionsArray = [];
  $openSectionAccordion= '';
  foreach($assignments as $singleAssignment) {
    $lxpLessonPost = get_post(get_post_meta($singleAssignment->ID, 'lxp_lesson_id', true));
    $sectionName = get_post_meta($lxpLessonPost->ID, 'lti_content_title', true);
    $openSectionAccordion = (isset($_GET['assignment_id']) && $_GET['assignment_id'] == $singleAssignment->ID ? $sectionName : $openSectionAccordion );
    $allSectionsArray[] = $sectionName;
  }
  $filterCourseSections = array_unique($allSectionsArray);
  $sectionNum = 1;
  $defaultLessonId = '';
  $defaultAssignment = '';
  $assignments = array_merge($assignments);
?>

<div class="accordion" style="width: 25%; height: 753px; overflow-y: scroll;" id="accordionExample">
  <?php foreach($filterCourseSections as $singleSection) {
    if ($sectionNum == '1' && !isset($_GET['assignment_id'])) {
      $sectionColor = '';
      $sectionLable = 'show';
    } elseif ($openSectionAccordion == $singleSection) {
      $sectionColor = '';
      $sectionLable = 'show';
    } else {
      $sectionColor = 'collapsed';
      $sectionLable = '';
    }
  ?>
    <div class="accordion-item">
      <h2 class="accordion-header">
          <button class="accordion-button <?php echo $sectionColor; ?> " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $sectionNum; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $sectionNum; ?>">
            <?php echo $singleSection; ?>
          </button>
      </h2>
      <?php
        $asignmentNum = 1;
        foreach($assignments as $key => $singleAssignment) {
          $lxpLessonPost = get_post(get_post_meta($singleAssignment->ID, 'lxp_lesson_id', true));
          $sectionName = get_post_meta($lxpLessonPost->ID, 'lti_content_title', true);
          if ($sectionName == $singleSection) {
            $curntessonLink = get_post_permalink($courseId).'?assignment_id='.$singleAssignment->ID;
      ?>
          <div id="collapse-<?php echo $sectionNum; ?>" class="accordion-collapse collapse <?php echo $sectionLable; ?>" data-bs-parent="#accordionExample">
            <?php
              if (isset($_GET['assignment_id']) && $_GET['assignment_id'] == $singleAssignment->ID) {
                $nextLessonLink = !next($assignments) ? get_post_permalink($courseId).'?assignment_id='.next($assignments)->ID : '#';
                $previousLessonLink = array_key_exists($key - 1, $assignments) ? get_post_permalink($courseId).'?assignment_id='.$assignments[$key -1]->ID : '#';
                $nextLessonLink = array_key_exists($key + 1, $assignments) ? get_post_permalink($courseId).'?assignment_id='.$assignments[$key +1]->ID : '#';
                $lessonColor = '#eee';
                $currentSectionName = $singleSection;
              } elseif (!isset($_GET['assignment_id']) && $asignmentNum == '1' && $sectionNum == '1') {
                $previousLessonLink = array_key_exists($key - 1, $assignments) ? get_post_permalink($courseId).'?assignment_id='.$assignments[$key -1]->ID : '#';
                $nextLessonLink = array_key_exists($key + 1, $assignments) ? get_post_permalink($courseId).'?assignment_id='.$assignments[$key +1]->ID : '#';
                $lessonColor = '#eee';
                $currentSectionName = $singleSection;
              } else {
                $lessonColor = '#fff';
              }
              if($lessonColor == '#eee') {
            ?>
              <input type="hidden" value="<?php echo $singleSection; ?>" id="currentSection">
              <input type="hidden" value="<?php echo $lxpLessonPost->post_title; ?>" id="currentLesson">
            <?php } ?>
              <div class="accordion-body" style="background: <?php echo $lessonColor; ?>;">
                <div class="polygon-shap" style="background-color: #1fa5d4">
                  <span>L</span>
                </div>&nbsp; &nbsp;<a href="<?php echo $curntessonLink ?>"><?php echo $lxpLessonPost->post_title.'<br>'; ?></a>
              </div>
          </div>
          <?php 
            $defaultLessonId = ($asignmentNum == '1' && $sectionNum == '1') ? $lxpLessonPost->ID : $defaultLessonId;
            $defaultAssignment = (!isset($_GET['assignment_id']) && $asignmentNum == '1' && $sectionNum == '1') ? $singleAssignment->ID : $defaultAssignment;
            ++$asignmentNum;
            }
        } ?>
    </div>
    <?php
      ++$sectionNum;
      }
    ?>
</div>

<div style="width: 74%" >
  <?php
  if (!isset($_GET['assignment_id'])) {
    $post->ID = $defaultLessonId;   // this is lesson id when page load by default first activity start open
    $_GET['assignment_id'] = $defaultAssignment;
  }
  // Start Set Current Assignment on blue boxes
  $calendar_selection_info = json_decode(get_post_meta($_GET['assignment_id'], 'calendar_selection_info', true));
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
  // End Set Current Assignment on blue boxes
  $post->ID = get_post_meta($_GET['assignment_id'], 'lxp_lesson_id', true);
  $assignmentType = get_post_meta($_GET['assignment_id'], 'assignment_type', true);
  if (!in_array($student_post->ID, get_post_meta($_GET['assignment_id'], 'attempted_students')) && $assignmentType == 'video_activity') {
    add_post_meta($_GET['assignment_id'], 'attempted_students', $student_post->ID);
  }
  lxp_check_assignment_submission($_GET['assignment_id'], $student_post->ID);
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
  $lessonCourseId = get_post_meta($post->ID, 'tl_course_id', true);
  $courseTitle = "";
  $coursePermaLink="";
  $args = array(
    'post_type' => TL_TREK_CPT,
    'orderby'    => 'ID',
    'post_status' => 'publish,draft',
    'order'    => 'DESC',
    'posts_per_page' => -1
  );
  $courses = get_posts($args);
  foreach( $courses as  $course ){
    if( $course->ID == $lessonCourseId ){
      $courseTitle = $course->post_title;
      $coursePermaLink = get_permalink($course->ID);
    }

  }
    $queryParam = '';
    if (isset($_GET["assignment_id"])) {
      $queryParam = $queryParam . "&assignment_id=" . $_GET["assignment_id"];	
    }
    $toolUrl = $toolUrl . $queryParam;
  ?>
  <input type="hidden" id="startDateTime" value="<?php echo $start; ?>" />
  <input type="hidden" id="endDateTime" value="<?php echo $end; ?>" />
  <iframe style="border: none;width: 100%;height: 706px;" class="" src="<?php echo site_url() ?>?lti-platform&post=<?php echo $post->ID ?>&id=<?php echo $attrId ?><?php echo $queryParam ?>" allowfullscreen></iframe>
  <div class="input_section">
    <div class="btn_box profile_buttons">
      <button class="btn profile_btn" type="button" <?php echo ($previousLessonLink == '#') ? 'disabled' : '' ?> onclick="go_to_url('<?php echo $previousLessonLink; ?>')" aria-label="Close" >Previous</button>

      <button class="btn profile_btn" <?php echo ($nextLessonLink == '#') ? 'disabled' : '' ?> onclick="go_to_url('<?php echo $nextLessonLink; ?>')" style="float: right;" >Next</button>
    </div>
  </div>
</div>