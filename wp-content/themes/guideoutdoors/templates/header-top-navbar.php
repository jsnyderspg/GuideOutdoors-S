<header class="banner navbar navbar-default navbar-static-top" role="banner">
	<div class="topbar-links mobileHide">
		<?php wp_nav_menu(array('theme_location' => 'top_links', 'menu_class' => 'container')); ?>
	</div>
	<script language = "javascript">
		$('document').ready(function(){
			//$(".menu-state-by-state a").html('');
			$(".menu-state-by-state a").prepend("<b class='abc1 li_abc1' style='float:right; margin:-15px 0 0 0'></b>");
			
		});
	</script>
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<div class="">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</div>
				<div class="navbar-toggle-label">Menu</div>
			</button>
			<a href="/" class="mobileShow mobileLogo">&nbsp;</a>
			<div class="navbar-mobile-right mobileShow">
				<span data-toggle="collapse" data-target=".navbar-mobile-search"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/mobile-search.png" height='20' width='20' /></span>&nbsp;&nbsp;<!--<a href="#"><img src='<?php echo get_template_directory_uri(); ?>/assets/img/icons/mobile-cart.png' height='20' width='20' /></a>-->
			</div> 
			<div class="navLeft no-left">
				<div class="brandContainer mobileHide no-left no-right">
					<a class="navbar-brand" href="<?php echo home_url(); ?>/"> <!--<?php bloginfo('name'); ?>--> <img src="<?php  echo get_template_directory_uri(); ?>/assets/img/logos/guideOutdoorsLogo.png" /> </a>
				</div>
				<div class="navSearch mobileHide">
				
						<img src="<?php  echo get_template_directory_uri(); ?>/assets/img/go_tagline.png" width="500px">
				
					<form id="search" name="searchform" method="get" class="search-form form-inline" action="<?php bloginfo("url"); ?>/">
						<section class="searchContainer">
						  <div class="input-group">
						    <input type="search" value="" name="s" class="search-field form-control" placeholder="Search Guide Outdoors">
						    <label class="hide">Search for:</label>
						    <span class="input-group-btn">
						      <button type="submit" class="search-submit btn btn-default">Search</button>
						    </span>
						  </div>					
						</section>
						<ul class="navSearchSelect">
							<li>
								<input type='radio' value='all' name='post_type' class='guideRadio' checked>
								<label class='radioLabel'>GUIDE OUTDOORS</label>
							</li>
							<li>
								<input type='radio' value='gear' name='post_type' class='gearRadio'>
								<label class='radioLabel'>SPORTSMANSGUIDE.COM</label>
							</li>
						</ul>
					</form>
				</div>
			</div>
			<div class="navCart mobileHide">
				<p><?php echo date_i18n('l, F j, Y', time()); ?></p>
				<!--<p><span> <a href='#'> <img src='<?php echo get_template_directory_uri(); ?>/assets/img/icons/cart.png' height='20' width='20'> VIEW CART </a> </span></p>-->
				<p><a href="/us/">About Guide Outdoors</a></p>
			</div>
			<div class="collapse navbar-mobile-search">
				<form role="search" method="get" class="search-form form-inline" action="/">
				  <div class="input-group" id="mobileInputGroup">
				    <input id="mobileSearchInput" autofocus="autofocus" type="search" value="" name="s" class="search-field form-control" placeholder="Search Guide Outdoors">
				    <label class="hide">Search for:</label>
				    <span class="input-group-btn">
				      <button type="submit" class="search-submit btn btn-default">Search</button>
				    </span>
				  </div>
				</form>
				<ul class="mobileSearchSelect"><!-- disabled 4/30/14 -->
					<li>
						<input name='searchSelect' type='radio' class='guideRadio' checked>
						<label class='radioLabel'>GUIDE OUTDOORS</label>
					</li>
					<li>
						<input name='searchSelect' type='radio' class='gearRadio'>
						<label class='radioLabel'>GEAR</label>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<nav class="collapse navbar-collapse" role="navigation">
				<?php
				if (has_nav_menu('primary_navigation')) :
					wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav', 'depth' => 0));
				endif;
				
				?>
			</nav>
		</div>
</header>