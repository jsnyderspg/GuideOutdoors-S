<?php setPostViews(get_the_ID()); //Update PageViews everytime when page is viewed ?>
<?php while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
        <header>
            <?php /* Check for bry's quick hits and hide the thumbnail. Otherwise, show it */ 
          if ( has_post_thumbnail() && !in_category('brys-quick-hits') ) {
                    the_post_thumbnail('large');
                } 
            ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <!-- AddThis Button BEGIN -->
	        <div class="addthis_toolbox addthis_default_style addthis_32x32_style share-buttons">
	        	<em>Share this post on social media:</em><br />
	            <?php if(comments_open()): ?>
	            <a class="comment" href="#respond"><span></span></a>
	            <?php endif; ?>
	            <a class="addthis_button_facebook facebook"><span></span></a>
	            <a class="addthis_button_twitter twitter"><span></span></a>
	            <a class="addthis_button_pinterest_share pin"><span></span></a>
	            <a class="addthis_button_google_plusone_share gplus"><span></span></a>
	            <a class="addthis_button_email email"><span></span></a>
	            <a class="addthis_button_print print"><span></span></a>
	            <span class="addthis_counter addthis_bubble_style"></span>
	        </div>
	        <!-- AddThis Button END -->
            <?php get_template_part('templates/entry-meta'); ?>
        </header>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php if ( get_post_type() == 'view_of_day' ) { ?>
				<strong>Photographer Name:</strong> <?php echo get_post_meta($post->ID, 'photographer_name', true); ?><br />
				<strong>Date:</strong> <?php echo get_post_meta($post->ID, 'view_date', true); ?><br />
				<strong>Location:</strong> <?php echo get_post_meta($post->ID, 'view_location', true); ?>
			<?php } ?>
			<?php if ( get_post_type() == 'trophies' ) { ?>
				<?php  $date = DateTime::createFromFormat('Ymd', get_field('trophy_date')); ?>
				<strong>Date:</strong> <?php echo get_post_meta($post->ID, 'trophy_date', true); /* echo $date.format('MM/DD/YYYY');*/ ?><br />
				<strong>Equipment Used:</strong> <?php echo get_post_meta($post->ID, 'trophy_equipment_used', true); ?><br />
				<strong>Location:</strong> <?php echo get_post_meta($post->ID, 'trophy_location', true); ?><br />
				<strong>Pictured (left to right):</strong> <?php echo get_post_meta($post->ID, 'trophy_names_picture', true); ?><br />
				<strong>Trophy Size:</strong> <?php echo get_post_meta($post->ID, 'trophy_size', true); ?><br />
				<strong>Trophy Species:</strong> <?php echo get_post_meta($post->ID, 'trophy_species', true); ?><br />
				<strong>Story:</strong> <?php echo get_post_meta($post->ID, 'trophy_story', true); ?>
			<?php } ?>
			<?php if ( get_post_type() == 'gear' ) { ?>
				<strong>Purchase <a style="text-decoration:underline;" target="_blank" href="<?php echo get_post_meta($post->ID, 'product_url', true); ?>"> <?php echo get_the_title($ID); ?></a> At <a style="text-decoration:underline;" href="http://www.sportsmansguide.com">Sportsman's Guide</a></strong> <br />
			<?php } ?>
        </div>
        <!-- AddThis Button BEGIN -->
        <div class="addthis_toolbox addthis_default_style addthis_32x32_style share-buttons">
        	<em>Share this post on social media:</em><br />
            <?php if(comments_open()): ?>
            <a class="comment" href="#respond"><span></span></a>
            <?php endif; ?>
            <a class="addthis_button_facebook facebook"><span></span></a>
            <a class="addthis_button_twitter twitter"><span></span></a>
            <a class="addthis_button_pinterest_share pin"><span></span></a>
            <a class="addthis_button_google_plusone_share gplus"><span></span></a>
            <a class="addthis_button_email email"><span></span></a>
			<?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>
           <!-- <a class="addthis_button_print print"><span></span></a>-->
            <span class="addthis_counter addthis_bubble_style"></span>
        </div>
        <!-- AddThis Button END -->
        <footer>
            <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
        </footer>
        <?php comments_template('/templates/comments.php'); ?>
    </article>
<?php endwhile; ?>
<!-- <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script> -->
<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52c6dc9d174b4743"></script>