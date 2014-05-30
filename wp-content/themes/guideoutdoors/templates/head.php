<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php wp_title('|', true, 'right'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--liveclicker-->
  <!-- old liveclicker -->
  <!-- <link  type="text/css"  rel="stylesheet" href="http://edge.liveclicker.net/scripts/client/1062/LCforTSGCustomCarousel.css"/> -->
<link  type="text/css"  rel="stylesheet" href="
http://edge.liveclicker.net/scripts/client/1062/LCforTSGCustomCarousel2.css
"/> 
  <?php wp_head(); ?>
  
  <!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>-->
  <!-- old liveclicker: <script type="text/javascript" src="http://edge.liveclicker.net/scripts/jquery.liveclicker.v1-29.js"></script>
  <script type="text/javascript" src="http://edge.liveclicker.net/scripts/jquery.jcarousel.min.js"></script>
  <script type="text/javascript" src="http://edge.liveclicker.net/scripts/client/1062/LCforTSGCustomCarousel.js"></script> -->
  <!--liveclicker--> <!-- new liveclicker code -->
       


  <!--liveclicker-->
  
  <!-- Slider / Carousel CSS -->
  <link href="<?php echo get_template_directory_uri(); ?>/assets/css/vendor/bxslider/jquery.bxslider-homeslider.css" rel="stylesheet" />
  <link href="<?php echo get_template_directory_uri(); ?>/assets/css/vendor/bxslider/jquery.bxslider-custom.css" rel="stylesheet" />
  
  <!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/ie8.css" />
  <![endif]-->

  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">
</head>
