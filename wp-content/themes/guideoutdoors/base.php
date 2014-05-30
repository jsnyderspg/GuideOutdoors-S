<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 8]>
    <div class="alert alert-warning">
      <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
    </div>
  <![endif]-->

  <?php
    do_action('get_header');
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
      get_template_part('templates/header-top-navbar');
    } else {
      get_template_part('templates/header');
    }
  ?>

  <div class="wrap container" role="document">
    <?php if(is_category()): ?>
    <?php
      $cat = get_queried_object();
      if(!$cat->category_parent):
    ?>
    <!--<div class="mobileHide">-->
	<div>
      <?php slider(); ?>
    </div>
  <!--  <div class="mobileShow">
      <?php /* slider('mobile'); */?>
    </div> -->
    <?php endif; ?>
    <?php endif; ?>
    <div class="content row">
      <main class="main <?php echo roots_main_class(); ?>" role="main">
      	<aside class="sidebar mobileShow <?php echo roots_sidebar_class(); ?>" role="complementary">
          <div class="row">
			<div class="col-lg-12">
				<span class="sidebarDropdown" data-toggle="collapse" data-target=".sidebar-collapse"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/backgrounds/sidebar_dropdown_m.png" /></span>
			</div>
		  </div>
          <div class="sidebar-collapse collapse">
          	<?php include roots_sidebar_path(); ?>
          </div>
       </aside>
       <?php get_template_part('templates/breadcrumb'); ?>
      	
        <?php include roots_template_path(); ?>
      </main><!-- /.main -->
      <?php if (roots_display_sidebar()) : ?>
        <aside class="sidebar <?php echo roots_sidebar_class(); ?>" role="complementary">
          <div class="desktopSidebar mobileHide">
          	<?php include roots_sidebar_path(); ?>
          </div>
        </aside><!-- /.sidebar -->
      <?php endif; ?>
    </div><!-- /.content -->
  </div><!-- /.wrap -->

  <?php get_template_part('templates/footer'); ?>

</body>
</html>