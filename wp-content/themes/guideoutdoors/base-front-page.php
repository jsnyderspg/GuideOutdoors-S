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
      <?php slider(); ?>
    <script>
      /*
      var viewportWidth, viewportHeight;
        $(function(){
          getViewport();
          getCarouselImages(function(){
            $('.homeSlider').bxSlider({
            //$('.homeSlider').slider({
              animation: "slide",
              prevText: "",
              nextText: "",
              useCSS: false,
            });
          });
          $(window).resize(function() {
            if($(window).width() != viewportWidth){
              getViewport();
              getCarouselImages();
            }
          });
        });
        function getCarouselImages(cb){
          $('.homeSlider .img').each(function(){
            var dvc = 'desktop';
            if(viewportWidth < 768){
              dvc = 'phone';
            }
            var src = $(this).attr('data-src-'+dvc);
            $(this).html('<img src="'+src+'" />');
          });
          if($('body').hasClass('home') && cb){
            cb();
          }
        }
        function getViewport(){
          viewportWidth = $(window).width();
          viewportHeight = $(window).height();
        }
        */
    </script>
    <?php spiffs(true); ?>
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
    <div class="content row">
      <main class="main <?php echo roots_main_class(); ?>" role="main">
        <?php include roots_template_path(); ?>
        <?php /*carousel('news'); ?>
        <?php carousel('tips'); ?>
        <?php carousel('adventures'); ?>
        <?php carousel('gear'); ?>
        <?php carousel('recipes'); ?>
        <?php carousel('post');*/ ?>
        <?php// author_carousel(); ?>
        <!-- Include LiveClicker code on homepage -->  
       
     <!--   <script type="text/javascript" src="//
ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
  <script type="text/javascript" src="http://edge.liveclicker.net/scripts/jquery.liveclicker.v1-33_c.js"></script>
        <script type="text/javascript" src="
http://sorgalla.com/jcarousel/dist/jquery.jcarousel.min.js"></script>
        <script type="text/javascript" src="
http://edge.liveclicker.net/scripts/client/1062/swipe.js"></script>
        <script type="text/javascript" src="
http://edge.liveclicker.net/scripts/client/1062/LCforTSGCustomCarousel3.js
"></script> 
        <!--liveclicker-->

  <!--liveclicker -->
  
		  <script>
		  var swidth = (window.innerWidth-32);
		  if (swidth > 590) { var vwidth = 590; } else { vwidth = swidth; }
		  	insertLCVideos("liveclicker","dim1","Browning",vwidth);
		  </script>
		  <div>
		  	<hr />
		  	<h2 class="sectionTitle">Videos</h2>
		  	<div id="liveclicker"></div>
		  </div> 
		  
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