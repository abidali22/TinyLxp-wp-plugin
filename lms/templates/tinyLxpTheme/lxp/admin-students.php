<?php
// get_template_part('lxp/functions');
global $treks_src;

// get all user with role lxp_client_admin
$lxp_client_admin_users = get_users(array('role' => 'lxp_client_admin'));
$lxp_client_admin_user_ids = array_map(function ($user) { return $user->ID; },  $lxp_client_admin_users);
// get post TL_DISTRICT_CPT based on multiple 'lxp_district_admin' meta values
$district_posts = get_posts(array(
  'post_type' => 'tl_district',
  'meta_query' => array(
    array(
      'key' => 'lxp_district_admin',
      'value' => $lxp_client_admin_user_ids,
      'compare' => 'IN'
    )
  )
));

$district_post = lxp_get_user_district_post( (isset($_GET['district_id']) ? get_post_meta($_GET['district_id'], 'lxp_district_admin', true) : 0) );
$district_schools = !$district_post ? [] : lxp_get_district_schools($district_post->ID);
$district_schools_ids = array_map(function ($school) { return $school->ID; },  $district_schools);
$district_schools_teachers = lxp_get_all_schools_teachers( isset($_GET['school_id']) ? [$_GET['school_id']] : $district_schools_ids );

global $userdata;
$teacher_post =  isset($_GET['teacher_id']) ? get_post($_GET['teacher_id']) : null;
$teacher_school_id = $teacher_post ? get_post_meta($teacher_post->ID, 'lxp_teacher_school_id', true) : 0;
$school_post = $teacher_school_id > 0 ? get_post($teacher_school_id) : null;
$students = [];
if(isset($_GET['school_id']) && isset($_GET['teacher_id'])) {
    $students = lxp_get_school_teacher_students($teacher_school_id, $teacher_post->ID);
} else if(isset($_GET['school_id'])) {
    $students = lxp_get_school_students($_GET['school_id']);
    $school_post = get_post($_GET['school_id']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Students</title>
    <link href="<?php echo $treks_src; ?>/style/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/header-section.css" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/schoolAdminTeachers.css" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/addNewTeacherModal.css" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/schoolDashboard.css" />
    <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/schoolAdminStudents.css" />
    <link href="<?php echo $treks_src; ?>/style/treksstyle.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    
    <style type="text/css">
        .heading-wrapper {
            border: 0px solid red;
            height: 115px;
        }

        .heading-left {
            float: left;
        }
        .heading-right {
            padding-top: 70px;
            padding-right: 20px;
            float: right;
        }

        .welcome-content {
            padding: 20px 0;
        }

        .add-teacher-box {
            display: block !important;
        }

        .add-heading {
            margin-top: 20px !important;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <?php include $livePath.'/trek/header-logo.php'; ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
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

    <!-- Nav Section -->
    <section class="main-container">
        <nav class="nav-section">
            <?php include $livePath.'/lxp/admin-nav.php'; ?>
        </nav>
    </section>

    <!-- Welcome: section-->
    <section class="welcome-section">
        <!-- Welcome: heading-->
        <div class="heading-wrapper">
            <div class="heading-left">
                <div class="welcome-content">
                    <h2 class="welcome-heading">Students</h2>
                    <p class="welcome-text">Student enrollment and registration management</p>
                </div>
            </div>

<!-- 
            <div class="heading-right">
                <a href="<?php //echo site_url("students"); ?>" type="button" class="btn btn-secondary btn-lg">Students</a>
                <a href="<?php //echo site_url("classes"); ?>" type="button" class="btn btn-outline-secondary btn-lg">Classes & Groups</a>
                <a href="<?php //echo site_url("groups"); ?>" type="button" class="btn btn-outline-secondary btn-lg">Groups</a>
            </div> -->
        </div>

        <!-- Total Schools: section-->
        <section class="school-section">
            <section class="school_teacher_cards">
                <div class="add-teacher-box">
                    <!-- <div class="search-filter-box">
                        <div class="search_box">
                            <label class="search-label">Search</label>
                            <input type="text" name="text" placeholder="School, ID, admin" />
                        </div>
                        <div class="filter-box">
                            <img src="<?php //echo $treks_src; ?>/assets/img/filter-alt.svg" alt="filter logo" />
                            <p class="filter-heading">Filter</p>
                        </div>
                    </div> -->

                    <!-- bootstrap html form with Dstricts, School and Teachers drop downs components with text search capabilities. These all drop downs use $district_posts, $district_schools and $district_schools_teachers php arrays for drop down items -->
                    <div class="row">
                        <div class="col-md-7">
                            <form class="row">
                                <div class="col-md-4">
                                    <label for="district-drop-down" class="form-label">District</label>
                                    <select class="form-select" id="district-drop-down" name="district_id">
                                        <option value="0">Choose...</option>
                                        <?php foreach ($district_posts as $district_post) { ?>
                                            <option value="<?php echo $district_post->ID; ?>"<?php echo isset($_GET['district_id']) && $_GET['district_id'] == $district_post->ID ? ' selected=selected' : '' ?>><?php echo $district_post->post_title; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="district-drop-down" class="form-label">School</label>
                                    <select class="form-select" id="school-drop-down" name="school_id">
                                        <option value="0">Choose...</option>
                                        <?php foreach ($district_schools as $district_school) { ?>
                                            <option value="<?php echo $district_school->ID; ?>"<?php echo isset($_GET['school_id']) && $_GET['school_id'] == $district_school->ID ? ' selected=selected' : '' ?>><?php echo $district_school->post_title; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="district-drop-down" class="form-label">Teacher</label>
                                    <select class="form-select" id="teacher-drop-down" name="teacher_id">
                                        <option value="0">Choose...</option>
                                        <?php foreach ($district_schools_teachers as $district_school_teacher) { ?>
                                            <option value="<?php echo $district_school_teacher->ID; ?>"<?php echo isset($_GET['teacher_id']) && $_GET['teacher_id'] == $district_school_teacher->ID ? ' selected=selected' : '' ?>><?php echo $district_school_teacher->post_title; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-5">
                            <div>
                                <button id="studentModalBtn" class="add-heading" type="button" data-bs-toggle="modal" data-bs-target="#studentModal" class="primary-btn">
                                    Add New Student
                                </button>
                                <label for="import-student" class="primary-btn add-heading">
                                    Import Students (CSV)
                                </label >
                                <input type="file" id="import-student" hidden />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <section class="recent-treks-section-div table-school-section">

                    <div class="students-table">
                        <!-- 
                        <div class="school-box">
                            <div class="showing-row-box">
                                <p class="showing-row-text">Showing 1 - 5 of 25</p>
                                <div class="row-box">
                                    <p class="showing-row-text">Rows per page</p>
                                    <div class="show-page">
                                        <button class="show-page-button" type="button" id="dropdownMenu2"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="showing-row-text">5</span>
                                        </button>
                                        <img id="dropdownMenu2" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false" src="<?php // echo $treks_src; ?>/assets/img//show-down-page.svg" alt="logo" />
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <button class="dropdown-item dropdown-class">
                                                <p class="page-row-para">1</p>
                                            </button>
                                            <button class="dropdown-item dropdown-class" type="button">
                                                <p class="page-row-para">2</p>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-box">
                                <p class="showing-row-text">First</p>
                                <img class="previous-slide-img" src="<?php // echo $treks_src; ?>/assets/img/previous-arrow.svg" alt="logo" />
                                <div class="slides-boxes">
                                    <div class="slide-box"><span class="showing-row-text slide-num">1</span></div>
                                    <div class="slide-box"><span class="showing-row-text slide-num slide-num2">2</span>
                                    </div>
                                    <div class="slide-box"><span class="showing-row-text slide-num slide-num2">3</span>
                                    </div>
                                </div>
                                <img class="last-slide-img" src="<?php // echo $treks_src; ?>/assets/img/last-slide.svg" alt="logo" />
                                <p class="showing-row-text">Last</p>
                            </div>
                        </div>
                        -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="">
                                        <div class="th1">
                                            Student
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th2">
                                            Username
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th3">
                                            Classes
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th3">
                                            Group
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th4">
                                            Grades
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th5">
                                            ID
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach ($students as $student) {
                                        $student_admin = get_userdata(get_post_meta($student->ID, 'lxp_student_admin_id', true));
                                        $student_id = get_post_meta($student->ID, 'student_id', true);
                                ?>
                                    <tr>
                                        <td class="user-box">
                                            <div class="table-user">
                                                <img src="<?php echo $treks_src; ?>/assets/img/profile-icon.png" alt="student" />
                                                <div class="user-about">
                                                    <h5><?php echo $student->post_title; ?></h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table-status"><?php echo $student_admin->user_login; ?></div>
                                        </td>
                                        <td>
                                            <?php 
                                                // echo count(lxp_get_student_all_classes($student->ID)); 
                                                echo count(lxp_get_student_class_group_by_type($student->ID, 'classes'));
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                echo count(lxp_get_student_class_group_by_type($student->ID, 'other_group'));
                                            ?>
                                        </td>
                                        <td class="grade">
                                            <?php 
                                                $student_grades = json_decode(get_post_meta($student->ID, 'grades', true));
                                                $student_grades = $student_grades ? $student_grades : array();
                                                foreach ($student_grades as $grade) {
                                            ?>
                                                <span><?php echo $grade; ?></span>
                                            <?php        
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $student_id ? $student_id : '--'; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="dropdown_btn" type="button" id="dropdownMenu2"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <img src="<?php echo $treks_src; ?>/assets/img/dots.svg" alt="logo" />
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                    <button class="dropdown-item" type="button" onclick="onStudentEdit(<?php echo $student->ID; ?>)">
                                                        <img src="<?php echo $treks_src; ?>/assets/img/edit.svg" alt="logo" />
                                                        Edit</button>
                                                    <!-- <button class="dropdown-item" type="button">
                                                        <img src="<?php // echo $treks_src; ?>/assets/img/delete.svg" alt="logo" />
                                                        Delete</button> -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- 
                        <div class="school-box">
                            <div class="showing-row-box">
                                <p class="showing-row-text">Showing 1 - 5 of 25</p>
                            </div>
                            <div class="row-box">
                                <p class="showing-row-text">First</p>
                                <img class="previous-slide-img" src="<?php // echo $treks_src; ?>/assets/img/previous-arrow.svg" alt="logo" />
                                <div class="slides-boxes">
                                    <div class="slide-box"><span class="showing-row-text slide-num">1</span></div>
                                    <div class="slide-box"><span class="showing-row-text slide-num slide-num2">2</span>
                                    </div>
                                    <div class="slide-box"><span class="showing-row-text slide-num slide-num2">3</span>
                                    </div>
                                </div>
                                <img class="last-slide-img" src="<?php // echo $treks_src; ?>/assets/img/last-slide.svg" alt="logo" />
                                <p class="showing-row-text">Last</p>
                            </div>
                        </div>
                         -->
                    </div>
                </section>
            </section>
        </section>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script
        src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
    <script src="<?php echo $treks_src; ?>/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    
    <?php 
        //if(isset($_GET['teacher_id'])) {
        if( $school_post ) {
            get_template_part('lxp/admin-student-modal', 'student-modal', array("school_post" => $school_post, "teachers" => $district_schools_teachers)); 
            $args['school_post'] = $school_post;
            $args['teachers'] = $district_schools_teachers;
            include $livePath.'/lxp/admin-student-modal.php';
        }

        if( !isset($_GET['teacher_id']) ) {
    ?>
        <div class="modal fade students-modal" id="studentModalAlert" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-title">
                            <h2 class="modal-title" id="studentModalLabel"><span class="student-action">New</span> Student</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Bootstrap alert with text: Please select District and School to add new student. -->
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Please select <strong>District, School</strong> and <strong>Teacher </strong> to add/edit a student.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery("#import-student").on("change", function(e) {
                    $('#studentModalAlert').modal('show');
                    jQuery("#import-student").val("");
                });
            });
        </script>
    <?php } ?>
    
    <?php if(isset($_GET['teacher_id'])) { ?>
        <input type="hidden" name="school_admin_id_imp" id="school_admin_id_imp" value="<?php echo get_post_meta( $school_post->ID, 'lxp_school_admin_id', true ); ?>">
        <input type="hidden" name="student_school_id_imp" id="student_school_id_imp" value="<?php echo $school_post->ID; ?>">
        <input type="hidden" name="teacher_id_imp" id="teacher_id_imp" value="<?php echo isset($_GET['teacher_id']) ? $_GET['teacher_id'] : 0; ?>">

        <script>
            jQuery(document).ready(function() {
                let host = window.location.hostname === 'localhost' ? window.location.origin + '/wordpress' : window.location.origin;
                let apiUrl = host + '/wp-json/lms/v1/';
                
                jQuery("#import-student").on("change", function(e) {
                    let formData = new FormData();
                    formData.append('student_school_id', jQuery("#student_school_id_imp").val());
                    formData.append('school_admin_id', jQuery("#school_admin_id_imp").val());
                    formData.append('teacher_id', jQuery("#teacher_id_imp").val());
                    formData.append('students', e.target.files[0]);
                    $.ajax({
                        method: "POST",
                        enctype: 'multipart/form-data',
                        url: apiUrl + "students/import",
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                    }).done(function( response ) {
                        jQuery("#import-student").val("");
                        window.location.reload();
                    }).fail(function (response) {
                        jQuery("#import-student").val("");
                        if (response.responseJSON) {
                            alert(response.responseJSON.data);
                        }
                    });
                });
            });
        </script>
    <?php } ?>

    <script>
        $(document).ready(function () {
            $('#district-drop-down').change(function () {
                var district_id = $(this).val();
                var url = new URL(window.location.href);
                url.searchParams.set('district_id', district_id);

                // unset school_id url param
                url.searchParams.delete('school_id');
                // unset teacher_id url param if it exists
                url.searchParams.delete('teacher_id');

                if (district_id == 0) {
                    url.searchParams.delete('district_id');
                }

                window.location.href = url.href;
            });

            $('#school-drop-down').change(function() {
                var school_id = $(this).val();
                var url = new URL(window.location.href);
                url.searchParams.set('school_id', school_id);

                // unset teacher_id url param if it exists
                url.searchParams.delete('teacher_id');

                if (school_id == 0) {
                    url.searchParams.delete('school_id');
                }
                window.location = url.href;
            });

            $('#teacher-drop-down').change(function() {
                var teacher_id = $(this).val();
                var url = new URL(window.location.href);
                url.searchParams.set('teacher_id', teacher_id);
                if (teacher_id == 0) {
                    url.searchParams.delete('teacher_id');
                }
                window.location = url.href;
            });

            $('#studentModalBtn').click(function() {
                if ($('#school-drop-down').val() == '0') {
                    $('#studentModalAlert').modal('show');
                }
            });
        });
    </script>
</body>

</html>