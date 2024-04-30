<style>
  
  a {
    text-decoration: none;
  }
  /* .accordion-body{
    border: 1px solid;
  } */
  .polygon-shap {
    float: left;
    width: 40px;
    height: 40px;
    background-color: #1fa5d4;
    clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .polygon-shap span {
    display: block;
    font-family: "Nunito";
    font-style: normal;
    font-weight: 700;
    font-size: 16px;
    line-height: 24px;
    color: #ffffff;
}
</style>
<?php
  $student_post = lxp_get_student_post(get_current_user_id());
  $assignments = lxp_get_student_assignments($student_post->ID);
  if (isset($args['course_id'])) {
    // $assignments filter by course id
    $assignments = array_filter($assignments, function ($assignment) use ($args) {
        return $assignment->course_id == $args['course_id'];
    });
  }
  $allSectionsArray = [];
  $openSectionAccordion= '';
  foreach($assignments as $singleAssignment) {
    $lxpLessonPost = get_post(get_post_meta($singleAssignment->ID, 'lxp_lesson_id', true));
    $sectionName = get_post_meta($lxpLessonPost->ID, 'lti_content_title', true);
    $openSectionAccordion = (isset($_GET['assignment_id']) && $_GET['assignment_id'] == $singleAssignment->ID ? $sectionName : $openSectionAccordion );
    $allSectionsArray[] = $sectionName;
  }
  $afterFilter = array_unique($allSectionsArray);
  $sectionNum = 1;
  $defaultLessonId = '';
  $defaultAssignment = '';
?>

<div class="accordion" style="width: 25%; height: 712px; overflow-y: scroll;" id="accordionExample">
  <?php foreach($afterFilter as $singleSection) { ?>
    <div class="accordion-item">
      <h2 class="accordion-header">
          <button class="accordion-button <?php echo ($sectionNum == '1' && !isset($_GET['assignment_id'])) ? '' : (($openSectionAccordion == $singleSection) ? '' : 'collapsed') ?> " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $sectionNum; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $sectionNum; ?>">
            <?php echo $singleSection; ?>
          </button>
      </h2>
      <?php
        $asignmentNum = 1;
        foreach($assignments as $singleAssignment) {
        $lxpLessonPost = get_post(get_post_meta($singleAssignment->ID, 'lxp_lesson_id', true));
        $sectionName = get_post_meta($lxpLessonPost->ID, 'lti_content_title', true);
        if ($sectionName == $singleSection) {
      ?>
          <div id="collapse-<?php echo $sectionNum; ?>" class="accordion-collapse collapse <?php echo ($sectionNum == '1' && !isset($_GET['assignment_id'])) ? 'show' : (($openSectionAccordion == $singleSection) ? 'show' : '') ?>" data-bs-parent="#accordionExample">
              <div class="accordion-body" style="background: <?php echo (isset($_GET['assignment_id']) && $_GET['assignment_id'] == $singleAssignment->ID) ? '#eee' : ((!isset($_GET['assignment_id']) && $asignmentNum == '1' && $sectionNum == '1') ? '#eee' : '#fff') ?>;">
                <div class="polygon-shap" style="background-color: #1fa5d4">
                  <span>L</span>
                </div>&nbsp; &nbsp;<a href="<?php echo get_post_permalink($args['course_id']).'?assignment_id='.$singleAssignment->ID; ?>"><?php echo $lxpLessonPost->post_title.'<br>'; ?></a>
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
  $queryParam = '';
?>
  <iframe style="border: none;width: 100%;height: 706px;" class="" src="<?php echo site_url() ?>?lti-platform&post=<?php echo $post->ID ?>&id=<?php echo $attrId ?><?php echo $queryParam ?>" allowfullscreen></iframe>
</div>