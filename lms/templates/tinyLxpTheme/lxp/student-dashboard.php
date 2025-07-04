<?php
// global $treks_src;
$userdata = get_userdata(get_current_user_id());
$student_post = lxp_get_student_post(get_current_user_id());
$assignments = lxp_get_student_assignments($student_post->ID);
$courses = lxp_get_assignments_courses($assignments);
$assignments_submissions = assignments_submissions($assignments, $student_post);

$statuses_count = array_reduce($assignments, function($carry, $assignment) use ($assignments_submissions) {
  $status_items = array_filter($assignments_submissions, function($submission) use ($assignment) {
    return isset($submission[$assignment->ID]);
  });
  $status = count($status_items) > 0 ? array_values($status_items)[0][$assignment->ID]['status'] : 'None';
  switch ($status) {
    case 'In Progress':
      $carry['inprogress']++;
      break;
    case 'Completed':
      $carry['completed']++;
      break;
    case 'To Do':
      $carry['todo']++;
      break;  
  }
  return $carry;
}, array('todo' => 0, 'inprogress' => 0, 'completed' => 0));
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student/Dashboard</title>
    <link href="<?php echo $treks_src; ?>/style/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/header-section.css" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/studentDashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />

    <style type="text/css">
      .treks-card {
        width: 300px !important;
      }
      .treks-card-link {
        text-decoration: none !important;
      }

      body {
          background-color: #f6f7fa !important;
      }
    </style>

  </head>

  <body>
    <nav class="navbar navbar-expand-lg bg-light">
      <div class="container-fluid">
        <?php include $livePath.'/trek/header-logo.php'; ?>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <div class="navbar-nav me-auto mb-2 mb-lg-0">
            <div class="header-logo-search">
              <!-- searching input -->
              <div class="header-search">
                <img src="<?php echo $treks_src; ?>/assets/img/header_search.svg" alt="svg" />
                <form action="<?php echo site_url("search"); ?>">
                    <input placeholder="Search" id="q" name="q" value="<?php echo isset($_GET["q"]) ? $_GET["q"]:''; ?>" />
                </form>
              </div>
            </div>
          </div>
          <div class="d-flex" role="search">
            <div class="header-notification-user">
                <?php include $livePath.'/trek/user-profile-block.php'; ?>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Basic Container -->
    <section class="main-container">
      <!-- Nav Section -->
      <nav class="nav-section">
        <?php include $livePath.'/trek/navigation-student.php'; ?>
      </nav>

    </section>

    <!-- main body section -->

    <section class="main-student-dashboard">
      <!-- welcome student  -->

      <section class="welcome-student">
        <div class="student-about">
          <img src="<?php echo $treks_src; ?>/assets/img/welcome.png" alt="welcome" />

          <div class="stu-about">
            <h1>Welcome <?php echo $userdata->display_name; ?>!</h1>
          </div>
        </div>
        <!-- Tags -->
        <div class="detail-prep-tags">
        </div>
      </section>

      <!-- Recent Course -->
      <section class="recent-treks-section stu-treks">
        <div class="recent-treks-section-div">
          <!--  Course header-->
          <div class="recent-treks-header section-div-header">
            <h2>Courses</h2>
            <div>
              <a href="<?php echo site_url("courses"); ?>">See All</a>
            </div>
          </div>
          <!-- Course cards -->
          <div class="recent-treks-cards-list">
            <!-- each cards  -->
            <?php foreach ($courses as $course) { ?>
              <!-- card -->
              <a href="<?php echo '../tl/course/'.get_post_field('post_name', $course->ID); ?>" class="treks-card-link">
                <div class="recent-treks-card-body treks-card">
                  <div>                    
                    <?php
                      if ( has_post_thumbnail( $course->ID ) ) {
                          echo get_the_post_thumbnail($course->ID, "medium", array( 'class' => 'rounded' )); 
                      } else {
                    ?>
                    <img width="300" height="180" src="<?php echo $treks_src; ?>/assets/img/tr_main.jpg" class="rounded wp-post-image" />
                    <?php        
                      }
                    ?>
                  </div>
                  <div>
                    <h3><?php echo get_the_title($course->ID); ?></h3>
                    <!-- <span>Due date: May 17, 2023</span> -->
                  </div>
                </div>
              </a>
            <?php } ?>
          </div>
        </div>
      </section>

      <!-- Assignments section  -->
      <section class="assignments-section">
        <!--  header -->
        <div class="heading">
          <h2>Assignments</h2>
        </div>
        <!-- assignments card -->
        <div class="assignments_label_card">
          <div class="assig_card">
            <label class="bg-gray">To Do</label>
            <h1 class="border-gray"><?php echo $statuses_count['todo']; ?></h1>
          </div>

          <div class="assig_card">
            <label class="bg-orange">In Progress</label>
            <h1 class="border-orange"><?php echo $statuses_count['inprogress']; ?></h1>
          </div>
          <div class="assig_card">
            <label class="bg-green">Submitted</label>
            <h1 class="border-green"><?php echo $statuses_count['completed']; ?></h1>
          </div>
        </div>
      </section>

      <!--repots  -->

      <div class="dropdown report-dropdown">
        <div
          class="input_dropdown dropdown-button"
          type="button"
          id="dropdownMenu2"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="true"
          data-bs-auto-close="false"
        >
          Reports
          <img src="<?php echo $treks_src; ?>/assets/img/down-arrow.svg" alt="logo" />
        </div>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
          <div class="report-table">
            <table>
              <thead>
                <tr>
                  <th>Course</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Teacher</th>
                </tr>
              </thead>
              <tbody>
                <!-- tr to iterate over assignments -->
                  <?php
                    foreach ($assignments as $assignment) { 
                      $course = get_post(get_post_meta($assignment->ID, 'course_id', true));
                      if (is_object($course)) {
                      $lxp_lesson_post = get_post(get_post_meta($assignment->ID, 'lxp_lesson_id', true));                      
                      $segmentColor = "#1fa5d4";
                      $status_items = array_filter($assignments_submissions, function($submission) use ($assignment) {
                        return isset($submission[$assignment->ID]);
                      });
                      $status = count($status_items) > 0 ? array_values($status_items)[0][$assignment->ID]['status'] : 'None';
                  ?>
                  <tr>
                    <td>
                      <div class="assignments-table-cs-td-poly">
                        <div class="polygon-shap" style="background-color: <?php echo $segmentColor; ?>">
                          <span><?php echo 'L'; ?></span>
                        </div>
                        <div>
                          <span style="color: <?php echo $segmentColor; ?>"><?php echo $lxp_lesson_post->post_title; ?></span>
                          <span><?php echo $course->post_title; ?></span>
                        </div>
                      </div>
                    </td>
                    <td>
                      <?php
                      // get start_date metadata from assignment and format it by month, date and year
                      $start_date = get_post_meta($assignment->ID, "start_date", true);
                      $start_date = date("M d, Y", strtotime($start_date));
                      echo $start_date;
                      ?>
                    </td>
                    <td>
                      <?php 
                      $assignment_grade = false;
                      // $slides = ["Overview" => 1, "Recall" => 2, "Practice A" => 3, "Practice B" => 4, "Apply" => 5, "YT Video" => 6, "T-Vid" => 7, "T-CP" => 8];
                      // $assignment_grade_key = "assignment_" . $assignment->ID . "_slide_" . $slides[$lxp_lesson_post->post_title] . "_grade";
                      // get student metadata for $assignment_grade_key
                      // $assignment_grade = get_post_meta($student_post->ID, $assignment_grade_key, true);
                      $assignment_submission_item = lxp_get_assignment_submissions($assignment->ID, $student_post->ID);
                      if ($assignment_submission_item) {
                        $assignment_grade = get_post_meta($assignment_submission_item['ID'], 'mark_as_graded', true);
                      }
                      ?>
                      <?php if ($assignment_grade == 'true') { ?>
                        <span class="grade-label graded-report">Grade</span>
                      <?php } elseif ($status === 'Completed') { ?>
                        <span class="grade-label submit-report">Submitted</span>
                      <?php } elseif ($status === 'In Progress') { ?>
                        <span class="grade-label pending-report">In Progress</span>
                      <?php } else { ?>
                        <span class="grade-label reviewed-report">To Do</span>
                      <?php } ?>
                    </td>
                    <td>
                      <div class="teacher">
                        <img src="<?php echo $treks_src; ?>/assets/img/profile-icon.png" alt="student" />
                        <h3>
                          <?php
                          // get assignment lxp_assignment_teacher_id metadata as $teacher_post_id
                          $teacher_post_id = get_post_meta($assignment->ID, "lxp_assignment_teacher_id", true);
                          // get teacher lxp_teacher_admin_id metadata as $teacher_admin_id
                          $teacher_admin_id = get_post_meta($teacher_post_id, "lxp_teacher_admin_id", true);
                          // get teacher user data by $teacher_admin_id as $teacher_user
                          $teacher_user = get_userdata($teacher_admin_id);
                          echo $teacher_user->display_name;
                          ?>
                        </h3>
                      </div>
                    </td>
                  </tr>
                <?php } } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <script
      src="https://code.jquery.com/jquery-3.6.3.js"
      integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
      crossorigin="anonymous"
    ></script>
    <script src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
    <script src="<?php echo $treks_src; ?>/js/custom.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>

    <script type="text/javascript">
      jQuery(document).ready(function () {
        window.report_dropdown = new bootstrap.Dropdown(document.getElementById('dropdownMenu2'));
        report_dropdown.show();
      });
    </script>
  </body>
</html>
