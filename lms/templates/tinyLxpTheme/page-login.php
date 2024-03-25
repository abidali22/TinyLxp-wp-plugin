<?php
$livePath = dirname( __FILE__ );
$treks_src = content_url().'/plugins/TinyLxp-wp-plugin/lms/templates/tinyLxpTheme/treks-src/';
// Start the loop.
// $courseId =  isset($_GET['courseid']) ? $_GET['courseid'] : get_post_meta($post->ID, 'tl_course_id', true);
// $args = array(
// 	'posts_per_page'   => -1,
// 	'post_type'        => 'tl_trek',
//   'order' => 'asc'
// );
// $treks = get_posts($args);
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
  <link href="<?php echo $treks_src; ?>/style/treksstyle.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />

    <style type="text/css">
      .treks-card {
        width: 300px !important;
      }
      .treks-card-link {
        text-decoration: none !important;
      }
      .lxp-login-container {
        width: 100%;
      }
      .recent-treks-section-div .recent-treks-cards-list {
        justify-content: center;
      }
      .login-submit input#wp-submit {
        padding: 6px 9px 6px 8px;
      }
    </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <?php include $livePath.'/trek/header-logo.php'; ?>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="navbar-nav me-auto mb-2 mb-lg-0">
          <div class="header-logo-search">

            <!-- searching input -->
            <!-- <div class="header-search">
              <img src="<?php //echo $treks_src; ?>/assets/img/header_search.svg" alt="svg" />
              <input placeholder="Search" />
            </div> -->
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
    
    <!-- Recent TREKs -->
    <section class="recent-treks-section">
      <div class="recent-treks-section-div">
        <!--  TREKs header-->
        <div class="recent-treks-header section-div-header">
          <h2>Login</h2>
          
        </div>
        <div class="lxp-login-container">
          <!-- TREKs cards -->
          <div class="recent-treks-cards-list">
            
            <?php   if (is_user_logged_in()) { ?>
              <a href="<?php echo wp_logout_url("login"); ?>">Logout</a>
            <?php   } ?>

            <?php
              if (!is_user_logged_in()) {
                $redirect_to = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : site_url("/dashboard");
                $args = array(
                  'echo' => true,
                  'label_username' => 'Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                  'redirect' => $redirect_to
                );
                wp_login_form($args); 
              }
            ?>
          </div>
        </div>
      </div>
    </section>
  </section>

  <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
    crossorigin="anonymous"></script>
  <script src="<?php echo $treks_src; ?>/js/Animated-Circular-Progress-Bar-with-jQuery-Canvas-Circle-Progress/dist/circle-progress.js"></script>
  <script src="<?php echo $treks_src; ?>/js/custom.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>
<?php endwhile; ?>