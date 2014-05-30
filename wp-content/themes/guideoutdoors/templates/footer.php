<footer class="content-info" role="contentinfo">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="joinBuyersClub">
					<h3><span>Join the Sportman's Guide Buyer's Club</span>&nbsp;&nbsp;&nbsp;&nbsp;$29.99 per year</h3>
					<h4>Save up to 10% on purchases every day.&nbsp;&nbsp;&nbsp;&nbsp;<span><a style="color:#efc74a;" href="http://www.sportsmansguide.com/club/clubtour">Click for More Info</a></span></h4>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-lg-12 footerNav mobileHide">
				<?php wp_nav_menu(array('theme_location' => 'footer_navigation')); ?>
			</div>
			<div class="col-lg-12 footerNavMobile mobileShow">
				<?php wp_nav_menu(array('theme_location' => 'footer_navigation')); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-md-6 emailSignUpContainer">
						<div class="emailSignUp">
							<h3>Sign up for email specials!</h3>
							<h4>Get advanced notice about upcoming sales &amp; catalog sneak peak previews</h4>
							<form role="search" method="get" class="search-form form-inline" action="/">
								<div class="input-group">
									<input type="search" value="" name="s" class="search-field form-control" placeholder="Enter your email address">
									<span class="input-group-btn">
										<button type="submit" class="btn btn-default">
											SIGNUP
										</button> </span>
								</div>
							</form>
						</div>
						<a href="#">Manage your email preferences</a>
					</div>
					<div class="col-md-6 appAdContainer mobileHide">
						<div class="appAd">
							<h3>TSG ANYWHERE</h3>
							<h4>Access The Sportsman's Guide anywhere with our TSG Anywhere App</h4>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				<?php dynamic_sidebar('sidebar-footer'); ?>
				<div class="row">
					<div class="col-md-12">
						<div class="copyright col-md-9">
							&copy; <?php echo date('Y'); ?>
							The Sportsman’s Guide, Inc.&nbsp;&nbsp;&nbsp;1-800-882-2962&nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">www.sportsmansguide.com</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">www.workwearsavings.com</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">www.truckmonkey.com</a>
						</div>
						<div class="footerSocial col-md-3">
							<a href="#"> <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/facebook.gif" /> </a>
							<a href="#"> <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/twitter.gif" /> </a>
							<a href="#"> <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/youtube.gif" /> </a>
							<a href="#"> <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/googleplus.gif" /> </a>
							<a href="#"> <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/pinterest.gif" /> </a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>

<!-- Slider / Carousel JS-->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/vendor/jquery.bxslider-homeslider.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/vendor/jquery.bxslider-custom.min.js"></script>
<script>
	var viewportWidth, viewportHeight;
	$("#menu-primary-navigation>li.megaMenu, #menu-primary-navigation>li.menu-newsblogs").each(function(index,el){
		$(el).mouseenter(function(e){
			$el = $(el);
			if (!$el.data('flyoutsLoaded')) {
				$el.data('flyoutsLoaded',true);
				$el.find('.mega-outer').each(function(index,el){
					var $el = $(el);
					$el.addClass('loading');
					var cat_id = $(el).closest('li').attr('id').replace('menu-item-','');

					$.get(ajaxurl_cache, { action: 'get_flyout_menu', cat_id: cat_id}, function(data) {
						$("nav #menu-item-" + data.ID + " .featured-outer").closest('.mega-outer').removeClass('loading');
						$("nav #menu-item-" + data.ID + " .featured-outer").html(data.featured);
						$("nav #menu-item-" + data.ID + " .recent-posts").html(data.recent);
			 		});
				});
			}
		});
	});
	function getSliderImages(reload){
		var slider = $('.homeSlider');
		slider.css({'opacity':0});
		slider.find('.img').each(function(){
			var dvc = 'desktop';
			if(viewportWidth < 768){
				dvc = 'mobile';
			}
			var src = $(this).attr('data-src-'+dvc);
			$(this).html('<img src="'+src+'" />');
		});

		var img    = slider.find('img').first();
		var imgInt = setInterval(function(){
			if(img.height() > 0){
				clearInterval(imgInt);
				if(reload){
					$('.homeSlider').parents('bx-viewport').height(img.height());
				}else{
					$('.homeSlider').homeSlider();
				}
				slider.animate({opacity: 1});
			}
		},100);

	}
	function getViewport(){
		viewportWidth = $(window).width();
		viewportHeight = $(window).height();
	}
	$(document).ready(function() {
		getViewport();
		getSliderImages();
		$(window).resize(function() {
			if($(window).width() != viewportWidth){
				getViewport();
				getSliderImages(true);
			}
		});
		if ( $(window).width() < 400 ) {
			//Functions for mobile portrait
			$('.customSlider').customSlider({
				minSlides : 2,
				maxSlides : 2,
				slideWidth : 100,
				slideMargin : 10,
				pager : false
			});
		} else if ( $(window).width() < 768 ) {
			//Functions for mobile landscape
			$('.customSlider').customSlider({
				minSlides : 2,
				maxSlides : 2,
				slideWidth : 160,
				slideMargin : 10,
				pager : false
			});
		} else if ( $(window).width() < 960 ) {
			//Functions for tablet portrait
			$('.customSlider').customSlider({
				minSlides : 3,
				maxSlides : 3,
				slideWidth : 120,
				slideMargin : 10,
				pager : false
			});
		} else {
			$('.customSlider').customSlider({
				minSlides : 3,
				maxSlides : 3,
				slideWidth : 160,
				slideMargin : 10,
				pager : false
			});
		}
	});
	
</script>

<!-- Menu Hover -->
<!--
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/plugins/bootstrap/bootstrap-hover-dropdown.js"></script>
-->
<!-- Respond.js -->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/plugins/respond.min.js"></script>
