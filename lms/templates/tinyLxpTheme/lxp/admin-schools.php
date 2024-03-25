<?php
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

$district_post = null;
if ( isset($_GET['district_id']) ) {
    $district_post = get_post( $_GET['district_id'] );
}

$schools = $district_post ? lxp_get_district_schools($district_post->ID) : [];

while (have_posts()) : the_post();
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php the_title(); ?></title>
        
        <link href="<?php echo $treks_src; ?>/style/main.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/header-section.css" />
        <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/schoolAdminTeachers.css" />
        <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/addNewTeacherModal.css" />
        <!-- <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/adminInternalTeacherView.css" /> -->
        <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/schoolDashboard.css" />
        <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/calendar.css" />
        <link rel="stylesheet" href="<?php echo $treks_src; ?>/style/adminSchools.css" />
        <link href="<?php echo $treks_src; ?>/style/treksstyle.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
            integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
            
        <!-- <style type="text/css">
            .treks-card {
                width: 300px !important;
            }

            .fc-content {
                padding: 7px;
            }

            .eventCloseBtn {
                padding: 3px;
                font-size: 18px;
            }

            .treks-card-link {
                text-decoration: none !important;
            }
        </style> -->
    </head>

    <body>
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <?php include $livePath.'/trek/header-logo.php'; ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="navbar-nav me-auto mb-2 mb-lg-0">
                        <div class="header-logo-search">

                            <!-- searching input -->
                            <div class="header-search">
                                <img src="<?php echo $treks_src; ?>/assets/img/header_search.svg" alt="svg" />
                                <input placeholder="Search" />
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

        
    <!-- School: section-->
    <section class="welcome-section">
        <!-- School: heading-->
        <div class="welcome-content">
            <h2 class="welcome-heading">Schools</h2>
            <p class="welcome-text">Manage your school operations from one central location</p>
        </div>

        <!-- Schools: section-->
        <section class="school-section">
            <section class="school_teacher_cards">
                <div class="add-teacher-box">
                    <div class="row" style="width: 50%;">
                        <div class="col-md-8">
                            <form class="row">
                                <div class="col-md-12">
                                    <label for="district-drop-down" class="form-label">District</label>
                                    <select class="form-select" id="district-drop-down" name="district_id">
                                        <option value="0">Choose...</option>
                                        <?php foreach ($district_posts as $dist_post) { ?>
                                            <option value="<?php echo $dist_post->ID; ?>"<?php echo isset($_GET['district_id']) && $_GET['district_id'] == $dist_post->ID ? ' selected=selected' : '' ?>><?php echo $dist_post->post_title; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">                   
                            <button class="add-heading" type="button" id="addSchoolModal" class="primary-btn" style="margin-top: 25px;">Add New School</button>
                        </div>
                    </div>
                </div>

                <!-- Admin School Table Section -->
                <section class="recent-treks-section-div table-school-section">

                    <div class="students-table">
                        <div class="school-box">
                            <div class="showing-row-box">
                                <!-- <p class="showing-row-text">Showing 1 - 5 of 25</p> -->
                                <!-- 
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
                                </div> -->

                            </div>
                            <!-- <div class="row-box">
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
                            </div> -->
                        </div>
                        <table class="table teacher_table">
                            <thead>
                                <tr>
                                    <th class="">
                                        <div class="th1">
                                            School
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th2">
                                            Administrator
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th3">
                                            ID
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th4">
                                            Teachers
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th5">
                                            Students
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th1 th5">
                                            Region / District
                                            <img src="<?php echo $treks_src; ?>/assets/img/showing.svg" alt="logo" />
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach ($schools as $school) {
                                ?>
                                    <tr>
                                        <td class="user-box">
                                            <div class="table-user">
                                                <img src="<?php echo $treks_src; ?>/assets/img/profile-icon.png" alt="school" />
                                                <div class="user-about">
                                                    <h5><?php echo $school->post_title ?></h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table-status"><?php echo get_userdata(get_post_meta($school->ID, 'lxp_school_admin_id', true))->display_name; ?></div>
                                        </td>
                                        <td><?php echo $school->ID; ?></td>
                                        <td><?php echo count(lxp_get_school_teachers($school->ID)); ?></td>
                                        <td><?php echo count(lxp_get_school_students($school->ID)); ?></td>
                                        <td>Texas</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="dropdown_btn" type="button" id="dropdownMenu2"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <img src="<?php echo $treks_src; ?>/assets/img/dots.svg" alt="logo" />
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                    <button class="dropdown-item" type="button" onclick="onSchoolEdit(<?php echo $school->ID; ?>)"><img src="<?php echo $treks_src; ?>/assets/img/edit.svg" alt="logo" />Edit</button>
                                                    <!-- <button class="dropdown-item" type="button"><img src="<?php // echo $treks_src; ?>/assets/img/delete.svg" alt="logo" />Delete</button> -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="school-box">
                            <!-- <div class="showing-row-box">
                                <p class="showing-row-text">Showing 1 - 5 of 25</p>
                            </div> -->
                            <!-- <div class="row-box">
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
                            </div> -->
                        </div>
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


        <!-- <form name="seach_form" method="get" action="">
            <input type="text" name="search_param" value="<?php // echo isset($_GET['search_param']) ? $_GET['search_param'] : '' ?>" />
            <input type="submit">
        </form> -->
        
        <?php // echo do_shortcode("[Schools-Short-Code]"); ?>
        <?php
            if (isset($_GET['district_id'])) {
                $args['district_post'] = $district_post;
                include $livePath.'/lxp/admin-school-modal.php';
            } else {
        ?>
                <div class="modal fade modal-lg" id="schoolModal" tabindex="-1" aria-labelledby="schoolModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered class-modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-header-title">
                                    <h2 class="modal-title" id="exampleModalLabel"><span class="school-action">New</span> School</h2>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Please select <strong>District</strong> to add/edit a School</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    jQuery(document).ready(function() {
                        var schoolModal = document.getElementById('schoolModal');
                        schoolModalObj = new bootstrap.Modal(schoolModal);
                        window.schoolModalObj = schoolModalObj;

                        jQuery("#addSchoolModal").on('click', function() {
                            schoolModalObj.show();
                        });
                    });
                </script>
        <?php
            }
        ?>

        <script type="text/javascript">
            function onSchoolEdit(school_id) {
                jQuery("#school_post_id").val(school_id);
                jQuery(".school-action").text("Update");
                
                let host = window.location.hostname === 'localhost' ? window.location.origin + '/wordpress' : window.location.origin;
                let apiUrl = host + '/wp-json/lms/v1/';

                $.ajax({
                    method: "POST",
                    enctype: 'multipart/form-data',
                    url: apiUrl + "schools",
                    data: {school_id}
                }).done(function( response ) {
                    let school = response.data.school;
                    let admin = response.data.admin.data;
                    jQuery('#schoolForm .form-control').removeClass('is-invalid');
                    jQuery('#inputSchoolName').val(school.post_title);
                    jQuery('#inputAbout').val(school.post_content);
                    jQuery('#inputFirstName').val(admin.first_name);
                    jQuery('#inputLastName').val(admin.last_name);
                    jQuery('#inputEmail').val(admin.user_email);
                    jQuery('#inputEmailDefault').val(admin.user_email);
                    
                    schoolModalObj.show();
                }).fail(function (response) {
                    console.error("Can not load school");
                });
            }

        </script>

        
    <script>
        $(document).ready(function () {
            $('#district-drop-down').change(function () {
                var district_id = $(this).val();
                var url = new URL(window.location.href);
                url.searchParams.set('district_id', district_id);

                if (district_id == 0) {
                    url.searchParams.delete('district_id');
                }

                window.location.href = url.href;
            });
        });
    </script>

    </body>
    </html>
<?php endwhile;
ob_end_flush(); ?>