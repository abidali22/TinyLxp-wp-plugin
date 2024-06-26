<?php
global $treks_src;

$assignment_id = intval( $_GET['assignment'] );
$student_id = 0;
if ( (isset($_GET['student']) && intval($_GET['student']) > 0) ) {
  $student_id = intval($_GET['student']);
}

$assignment = lxp_get_assignment($assignment_id);
$assignment_submission = lxp_get_assignment_submissions($assignment->ID, $student_id);
$submit_status = (isset($assignment_submission['submission_id']) && $assignment_submission['submission_id'] != 0);
if ($submit_status) {
    $mark_as_graded = $assignment_submission ? get_post_meta($assignment_submission['ID'], 'mark_as_graded', true) : null;

    $local_user_id = get_post_meta($student_id, 'lxp_student_admin_id', true);
    $lesson_id = get_post_meta($assignment_id, 'lxp_lesson_id', true);
    global $wpdb;
    $current_lesson_id = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "tiny_lms_grades WHERE lesson_id = " . $lesson_id . " AND user_id= " . $local_user_id);
        if( empty($current_lesson_id) == false ){
            $give_lesson = $current_lesson_id[0];
        }
    $lesson_score = (isset($give_lesson->score)) ? ($give_lesson->score * 100 .' / 100') : '---';
}
?>

<style type="text/css">
    .student_grade_card {
        margin-top: 15px;
    }
</style>

<div class="tab-content" id="myTabContent">
    <!-- Teachers Table -->
    <?php if ($submit_status) {  ?>
        <div class="row justify-content-end">
            <div class="col-md-3">
                <div class="row justify-content-end">
                    <div class="col-md-12">
                        <a href="<?php echo site_url('grade-assignment/?assignment=' . $_GET['assignment'] . '&student='. $_GET['student'] . '&lesson_id=' . $lxp_lesson_post->ID . '&student_id=' . $local_user_id); ?>" rel="permalink" class="primary-btn lx-space summary_link">
                        View Summary</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row justify-content-start">
                    <div class="col-md-10">
                        <div class="btn btn-info" role="alert">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="markGraded" <?php echo $mark_as_graded === 'true' ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="markGraded"><strong>Mark Graded</strong></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <h1 class="stu_heading">Submissions</h1>
    <?php } ?>
    <div
        class="tab-pane fade show active"
        id="one-tab-pane"
        role="tabpanel"
        aria-labelledby="one-tab"
        tabindex="0"
    >
    
        <div class="slider_cards_flex">
            <?php 
            if ($submit_status) {
            ?>
                <div class="student_grade_card border">
                    <span class="student_slide green_slide"><?php echo $lxp_lesson_post->post_title; ?></span>
                    <p>&nbsp;</p>
                    <h2 class="gray_grade"> <?php echo $lesson_score; ?> </h2>
                    <br />
                    <a href="#"><span class="badge bg-secondary" style="margin-bottom:18px;"> Auto-graded </span></a>
                    <br />
                    <img src="<?php echo $treks_src; ?>/assets/img/check-g.svg" alt="" class="check-g" />
                </div>
            <?php } else { ?>
                <p>Student has not submitted yet.</p>
            <?php } ?>
        </div>
    </div> 
</div>

<?php
    // function getMetaScore($assignment_id, $student_id) {
        
        
    //     $assignment_submission_posts = get_post($assignment_id);
    //     var_dump($assignment_submission_posts); exit;
    //     if ($assignment_submission_posts) {
    //         $assignment_submission_post = $assignment_submission_posts[0]->ID;
    //         $submission = get_post_meta($assignment_submission_post);
    //         if ($submission && isset($submission['score_raw']) && isset($submission['score_max'])) {
    //             return $submission['score_raw'][0] .' / '. $submission['score_max'][0];
    //         } else {
    //             return '---';
    //         }
    //     }
    // }
?>