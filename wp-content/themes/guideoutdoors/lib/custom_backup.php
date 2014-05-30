<?php

// *************************************************
//  WP HOOKS
// *************************************************

// Add custom post types to WP category/tag pages if the type is set (i.e. "View All" button in carousel was clicked)
add_filter( 'pre_get_posts', 'spg_add_custom_types' );
function spg_add_custom_types( $query ) {
    if(is_main_query() && empty( $query->query_vars['suppress_filters'] )) {
        if( (is_category() || is_tag()) ) {
            $post_type = $_REQUEST['type'];
            if ( $post_type ) {
                $query->set( 'post_type', array(
                    $post_type
                ));
            }
        }
        if(is_author()){
            $query->set( 'post_type', array(
                'adventures',
                'recipes',
                'tips',
                'gear',
                'buyers_guides',
                'news'
            ));        }
        if ($query->is_search or $query->is_feed) {
                // Gear
                if($_GET['post_type'] == "gear") {
                    $query->set('post_type', array('gear'));
                }
                // Everything
                elseif($_GET['post_type'] == "all") {
                    $query->set('post_type', array('adventures', 'tips', 'recipes', 'buyers_guides', 'news', 'gear', 'view_of_day'));
                }
            }
        }
    return $query;
}

// Remove certain plugin JavaScript files
add_action( 'wp_print_scripts', 'spg_deregister_js', 100 );
function spg_deregister_js() {
    // Popular Widget Script
	wp_deregister_script( 'popular-widget' );
}
// Remove certain plugin CSS files
add_action( 'wp_print_styles', 'spg_deregister_css', 100 );
function spg_deregister_css() {
    // Popular Widget CSS
	wp_deregister_style( 'popular-widget' );
}

// Lower the Yoast SEO MetaBox priority
add_filter( 'wpseo_metabox_prio', 'set_wpseo_metabox_priority'); 
function set_wpseo_metabox_priority(){
  return 'low';
} 

// Add column in admin to show Featured status
add_filter('manage_posts_columns', 'spg_columns_head');
function spg_columns_head($defaults) {
    $defaults['is_featured'] = 'Featured?';
    $post_type_arr = spg_get_featured_types();
    foreach($post_type_arr as $type):
        add_filter( "manage_edit-".$type."_sortable_columns", "spg_columns_sortable_register" );
    endforeach;
    return $defaults;
}
 
// Show the featured status of a post in the WP admin columns
add_action('manage_posts_custom_column', 'spg_columns_content', 10, 2);
function spg_columns_content($column_name, $post_ID) {
    if ($column_name == 'is_featured') {
        $meta = get_post_meta($post_ID);
        $is_featured = $meta['featured_general'][0];
        if ($is_featured) {
            echo '&#x2713;';
        }
    }
}

// Register our custom Featured column for sorting
function spg_columns_sortable_register( $columns ) {
    $columns["is_featured"] = "is_featured";
    return $columns;
}

// Make the column custom Featured column sortable
add_action( 'pre_get_posts', 'spg_columns_sortable' );
function spg_columns_sortable( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
    if( 'is_featured' == $orderby ) {
        $query->set('meta_key','featured_general');
        $query->set('orderby','meta_value');
    }
}

// Add image sizes
add_image_size( 'thumbnail-large', 250, 250, true );
add_image_size( 'slide-desktop', 1060, 358, true );
add_image_size( 'slide-mobile', 640, 400, true );

// *************************************************
//  CUSTOM FUNCTIONS
// ************************************************

// Utility function to pretty-print code to the page
function pre($code){
    echo '<pre>'.print_r($code,true).'</pre>';
}

/*
// -----------------------------------------------
//  slider()
//  - Display the main mast-head featured image slider
//  - This is used in two places:
//      * Home page
//      * Top-level category pages
//  ### THE HTML RETURNED BY THIS FUNCTIONED NEEDS TO BE UPDATED FOR FE DEV NEEDS
// -----------------------------------------------
*/

function slider($view = 'desktop'){
    $slides = get_slider();
    $view = ($view == 'mobile') ? 'slide-mobile' : 'slide-desktop';
    ?>
    <?php if($slides->post_count >=1): ?>
    <ul class="homeSlider">
       <!--<?php echo (is_front_page()) ? 'Home' : 'Category';?>-->
        <?php foreach($slides->posts as $slide): ?>
        <li>
        	<a href='<?php echo get_permalink( $slide->ID ); ?>'>
        		<?php echo get_the_post_thumbnail( $slide->ID, $view ); ?>
	        	<div class="homeSliderTitle">
	        		<h3><?php echo $slide->post_title; ?></h3>
	        	</div>
        	</a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
<?php 
}
/*
// -----------------------------------------------
//  get_slider()
//  - Display the main mast-head featured image slider
//  - This is used in two places:
//      * Home page
//      * Top-level category pages
//  ### THE HTML RETURNED BY THIS FUNCTIONED NEEDS TO BE UPDATED FOR FE DEV NEEDS
// -----------------------------------------------
*/

function get_slider(){
    $key = 'featured_general';
    $args = array(
        'post_type' => array('adventures','post','tips','recipes', 'news'),
        'posts_per_page' => 5,
        'meta_query' => array(
            array(
                'key' => $key,
                'value' => 1,
            )
        )
    );
    if(is_category()){
        $cat = get_query_var('cat');
        $args['cat'] = $cat;
    }
    return new WP_Query($args);
}
/*
// -----------------------------------------------
//  carousel()
//  - Output an HTML list of posts to the page for use in a JS carousel
//  - Possible variables include:
//      * $post_type | Post type to use                           | Default: array('post')
//      * $limit     | Number of posts to load initially          | Default: 6
//      * $cat       | Filter posts by category taxonomy          | Default: '' (none)
//      * $geo       | Filter posts by geographic region taxonomy | Default: '' (none)
//  - Example uses:
//      <?php carousel(); ?>
//      <?php carousel('adventures'); ?>
//      <?php carousel('tips', 6, '', 'alabama'); ?>
//          ** NOTE: In most cases, category and geography 
//             variables will be handled by the templates
//  ### THE HTML RETURNED BY THIS FUNCTIONED MAY NEED TO BE UPDATED FOR FE DEV NEEDS
// -----------------------------------------------
*/
function carousel($post_type = array('post'), $limit = 6, $cat = '', $geo = ''){
    $slides = get_carousel($post_type, $limit, $cat, $geo);
    ?>
    <?php if($slides->posts->post_count >=1): ?>
    	<hr />
        <h2 class="sectionTitle"><?php echo $slides->post_type_obj->labels->name; ?></h2>
        <a class="btn buttonPrimary" href="<?php echo $slides->view_all;?>">View All</a>
        <div class="clearfix"></div>
        <div class="post-carousel" data-page="2" data-type="<?php echo $post_type; ?>" data-limit="<?php echo $limit; ?>" data-geo="<?php echo $geo; ?>" data-cat="<?php echo $cat; ?>">
            
            <ul class="customSlider">
            <?php foreach($slides->posts->posts as $slide): ?>
                <li>
                    <a href='<?php echo get_permalink( $slide->ID ); ?>'>
                        <div class="thumb-container">
                        	<div class="thumb-title">
                        		<h3><?php echo $slide->post_title; ?></h3>
                        	</div>
                        	<?php echo (has_post_thumbnail( $slide->ID )) ? get_the_post_thumbnail( $slide->ID, 'thumbnail' ) : '<img src="/wp-content/uploads/TSG_placeholder_image.jpg" />'; ?>
                        </div>
                    </a>
                    <p class="entry-summary">
                        <span class='date'><?php echo date('M j, Y', strtotime($slide->post_date)); ?> -</span>
                        <!--<span class='author'><?php echo strtoupper($slide->post_author); ?> -</span>-->
                        <span class='description'><?php echo strlen($slide->post_excerpt) > 110 ? substr(strip_shortcodes(strip_tags($slide->post_excerpt)),0,110)."..." : strip_shortcodes(strip_tags($slide->post_excerpt)); ?></span>
                    </p>
                    <a href='<?php echo get_permalink( $slide->ID ); ?>' class="readMore">READ MORE</a>
                </li>
            <?php endforeach; ?>
            </ul>
            <?php if($slides->posts->max_num_pages > 1): ?><button class="carousel-next">Next</button><?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if($post_type == 'recipes'): ?>
    	<a class="btn buttonSecondary" href="/share/recipe/">Submit Your Own</a>
    	<div class="clearfix"></div>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
<?php }
		/*
		// -----------------------------------------------
		//  get_carousel()
		//  - Returns an array of WP Post Objects for use in a carousel
		//  - Possible variables include:
		//      * $post_type | Post type to use                           | Default: array('post')
		//      * $limit     | Number of posts to load initially          | Default: 6
		//      * $cat       | Filter posts by category taxonomy          | Default: '' (none)
		//      * $geo       | Filter posts by geographic region taxonomy | Default: '' (none)
		//  - Example uses:
		//      <?php carousel(); ?>
		//      <?php carousel('adventures'); ?>
		//      <?php carousel('tips', 6, '', 'alabama'); ?>
		//          ** NOTE: In most cases, category and geography
		//             variables will be handled by the templates
		// -----------------------------------------------
		*/
		function get_carousel($post_type = array('post'), $limit = 6, $cat = '', $geo = ''){
		$post_type_obj = get_post_type_object( $post_type );
		$args = array(
		'post_type' => $post_type,
		'posts_per_page' => $limit,
		);
		if($cat){
		$args['tax_query'][] = array(
		'taxonomy' => 'category',
		'field' => 'slug',
		'terms' => array( $cat )
		);
		$cat_id = get_category_by_slug( $cat )->term_id;
		}
		if($geo){
		$args['tax_query'][] = array(
		'taxonomy' => 'geography',
		'field' => 'slug',
		'terms' => array( $geo )
		);
		}
		if(!$cat && !$geo){
		$viewAllHref = get_post_type_archive_link( $post_type );
		}else{
		$viewAllHref = ($cat) ? get_category_link( $cat_id ) . '?type=' . $post_type : get_term_link( $geo, 'geography' ) . '?type=' . $post_type;
		}
		$carousel = new stdClass();
		$carousel->view_all = $viewAllHref;
		$carousel->post_type_obj = $post_type_obj;
		$carousel->posts = new WP_Query($args);
		return $carousel;
		}
		/*
		// -----------------------------------------------
		//  author_carousel()
		//  - Output an HTML list of authors to the page for use in a JS carousel
		//  ### THE HTML RETURNED BY THIS FUNCTIONED MAY NEED TO BE UPDATED FOR FE DEV NEEDS
		// -----------------------------------------------
		*/
		function author_carousel($cat = '', $geo = ''){
		$slides = get_author_carousel($cat, $geo);
    ?>
    <?php if(count($slides)>=1): ?>
    	<hr />
        <h2 class="sectionTitle">Contributors</h2>
        <a class="btn buttonPrimary" href="<?php echo $slides->view_all;?>">View All</a>
        <div class="clearfix"></div>
        <div class="post-carousel">
            <ul class="customSlider">
            <?php foreach($slides as $user):?>
                <?php $meta = get_user_meta($user -> ID);
				$link = get_author_posts_url($user -> ID);
                ?>
                <li>
                    <a href='<?php echo $link; ?>'>
                         
                    </a>
                    
                    <a href='<?php echo $link; ?>'>
                        <div class="thumb-container">
                        	<div class="thumb-title">
                        		<h3><?php echo $user -> display_name; ?></h3>
                        	</div>
                        	<?php echo get_avatar($user -> ID, 'thumbnail'); ?>
                        </div>
                    </a>
                    <p class="entry-summary">
                        <span class='description'><?php echo (strlen($user -> description) > 110) ? substr($user -> description, 0, 107) .  '...' : $user -> description; ?></span><br />
                    	<a href='<?php echo $link; ?>'  class="readMore">READ MORE</a>
                    </p>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
<?php }
		/*
		// -----------------------------------------------
		//  get_author_carousel()
		//  - Returns an array of WP User Objects for use in a carousel
		// -----------------------------------------------
		*/
		function get_author_carousel(){
		global $wpdb;
		global $wp_query;
		$tax = (is_tax() || is_category() || is_tag()) ? true : false;
		$term = ($tax) ? $wp_query->get_queried_object() : "";
		$user_query = "
		SELECT DISTINCT wp_posts.post_author as ID
		FROM wp_posts ";
		$user_query .= ($tax) ? "INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) " : "";
		$user_query .= "WHERE 1=1 ";
		$user_query .= ($tax) ? "AND ( wp_term_relationships.term_taxonomy_id IN (".$term->term_taxonomy_id.") ) " : "";
		$user_query .= "
		AND wp_posts.post_type IN ('recipes','adventures','post','news', 'buyers_guides', 'tips', 'gear')
		AND wp_posts.post_status = 'publish'
		ORDER BY wp_posts.post_date DESC
		LIMIT 0, 5";
		$results = $wpdb->get_results($user_query, OBJECT);
		$userIDs = array();
		foreach($results as $user){
		$userIDs[] = $user->ID;
		}
		return get_users( array('include' => $userIDs) );
		}
		/*
		// -----------------------------------------------
		//  state_select()
		//  - Display a <select> box to choose a state
		//  - State names available as <option>s come from the Geography taxonomy
		//  ### THE HTML RETURNED BY THIS FUNCTIONED MAY NEED TO BE UPDATED FOR FE DEV NEEDS
		// -----------------------------------------------
		*/
		function state_select(){
		$state_select = get_terms( 'geography' );
    ?>
    <?php if($state_select[0]): ?>
        <form class="state-form" action="../">
        	<select class="state-select form-control" name="stateSelect">
	            <option>Filter Guide Outdoors by State</option>
	            <?php foreach($state_select as $state): ?>
	            <option value="<?php echo get_term_link($state -> slug, 'geography'); ?>"><?php echo $state -> name; ?></option>
	            <?php endforeach; ?>
       		</select>
       		<input class="state-button btn buttonPrimary" type="button" value="GO" onclick="ob=this.form.stateSelect;window.open(ob.options[ob.selectedIndex].value,'_top')"/>
       	</form>
    <?php endif; ?>
<?php }
		/*
		// -----------------------------------------------
		//  spiffs()
		//  - Output Spiffs to the page
		//  - Possible variables include:
		//      * $featured (Boolean)  | Whether the spiffs displayed have been checked as featured     | Default: false
		//          ** This is an IF/ELSE, so either featured Spiffs are returned,
		//             or those that are not featured. Both will not be returned
		//             at the same time.
		//
		//  ### THE HTML RETURNED BY THIS FUNCTIONED MAY NEED TO BE UPDATED FOR FE DEV NEEDS
		// -----------------------------------------------
		*/
		function spiffs($featured = false){
		$spiffs = get_spiffs($featured);
		if($spiffs[0]):
    ?>
    <div class="spiffs-container widget <?php echo ($featured) ? 'row' : ''; ?>">
    <?php foreach($spiffs as $spiff): ?>
    <?php setup_postdata($spiff); ?>
        <?php $meta = get_post_meta($spiff -> ID);
		$showTitle = $meta['spiff_display_title'][0];
		$link = $meta['spiff_link'][0];
		$thumb = get_the_post_thumbnail($spiff -> ID, 'thumbnail-large');
        ?>
        <div class="<?php echo ($featured) ? 'featuredSpiff' : ''; ?>">
            <?php if($featured): ?>
        	<a href="<?php echo $link; ?>">
        		<div class="thumb-container">
		            <div class="thumb-title">
		            	<?php if($showTitle): ?>
			                <h3><?php echo make_link($spiff -> post_title, $link); ?></h3>
			            <?php endif; ?>
		            </div>
		            <?php echo ($thumb) ? $thumb : ''; ?>
                </div>
            </a>
            <?php else: ?>
            <h3><?php echo make_link($spiff -> post_title, $link); ?></h3>
            <div class="spiff-content">
                <?php echo ($thumb) ? $thumb : ''; ?>
                <?php echo apply_filters('the_content', $spiff->post_content); ?>
            </div>
            <?php endif; ?>
         </div>
    <?php endforeach; ?>
    </div>
    <?php wp_reset_postdata(); ?>
    <?php endif; ?>
<?php }
		/*
		// -----------------------------------------------
		//  get_spiffs()
		//  - Returns spiffs in an array of WP Post Objects
		//  - Possible variables include:
		//      * $featured (Boolean)  | Whether the spiffs displayed have been checked as featured     | Default: false
		//          ** This is an IF/ELSE, so either featured Spiffs are returned,
		//             or those that are not featured. Both will not be returned
		//             at the same time.
		// -----------------------------------------------
		*/
		function get_spiffs($featured = false){
		if(!is_author()){
		global $wpdb;
		global $post;
		global $wp_query;
		$today = date('Y/m/d');
		$tax = (is_tax() || is_category() || is_tag()) ? true : false;
		$term = ($tax) ? $wp_query->get_queried_object() : "";
		$spiffQuery = "SELECT SQL_CALC_FOUND_ROWS  * FROM wp_posts ";
		$spiffQuery .= ($tax) ? "INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) " : "";
		$spiffQuery .= "
		INNER JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id) " .
		"INNER JOIN wp_postmeta AS mt1 ON (wp_posts.ID = mt1.post_id) " .
		"INNER JOIN wp_postmeta AS mt2 ON (wp_posts.ID = mt2.post_id) " .
		"INNER JOIN wp_postmeta AS mt3 ON (wp_posts.ID = mt3.post_id) " .
		"WHERE 1=1 ";
		$spiffQuery .= ($tax) ? "AND ( wp_term_relationships.term_taxonomy_id IN (".$term->term_taxonomy_id.") ) " : "";
		$spiffQuery .= "
		AND wp_posts.post_type = 'spiff' " .
		"AND wp_posts.post_status = 'publish' " .
		"AND (
		(
		(wp_postmeta.meta_key = 'spiff_start_date' AND CAST(wp_postmeta.meta_value AS DATE) <= '".$today."')
		AND (mt1.meta_key = 'spiff_end_date' AND CAST(mt1.meta_value AS DATE) >= '".$today."')
		)
		OR (
		(wp_postmeta.meta_key = 'spiff_start_date' AND wp_postmeta.meta_value = '')
		AND (mt1.meta_key = 'spiff_end_date' AND mt1.meta_value = '')
		)
		OR (
		(wp_postmeta.meta_key = 'spiff_start_date' AND wp_postmeta.meta_value = '')
		AND (mt1.meta_key = 'spiff_end_date' AND CAST(mt1.meta_value AS DATE) >= '".$today."')
		)
		OR (
		(wp_postmeta.meta_key = 'spiff_start_date' AND CAST(wp_postmeta.meta_value AS DATE) <= '".$today."')
		AND (mt1.meta_key = 'spiff_end_date' AND mt1.meta_value = '')
		)
		) ";
		$spiffQuery .= (!$tax) ? "AND (mt2.meta_key = 'spiff_pages' AND CAST(mt2.meta_value AS CHAR) LIKE '%".$post->ID."%') " : "";
		$spiffQuery .= ($featured) ? "AND (mt3.meta_key = 'spiff_featured' AND CAST(mt3.meta_value AS CHAR) = '1') " : "AND (mt3.meta_key = 'spiff_featured' AND CAST(mt3.meta_value AS CHAR) != '1') ";
		$spiffQuery .= "GROUP BY wp_posts.ID ORDER BY wp_posts.menu_order ASC, wp_posts.post_date DESC ";
		$spiffQuery .= ($featured) ? "LIMIT 0, 4" : "";
		return $wpdb->get_results($spiffQuery, OBJECT);
		}
		return false;
		}
		/*
		// -----------------------------------------------
		//  make_link()
		//  - Converts a string to a link
		// -----------------------------------------------
		*/
		function make_link($string = '', $url = ''){
		if(!$string || !$url){
		return $string;
		}
		return '<a href="'.$url.'">'.$string.'</a>';
		}
		/*
		// -----------------------------------------------
		//  author_card()
		//  NEEDS DOCUMENTATION
		// -----------------------------------------------
		*/
		function author_card(){
		if(is_single() || is_author()):
            global $post;
            $uID = (is_single()) ? $post -> post_author : get_queried_object()->data->ID;
            $meta = get_user_meta( $uID );
 ?>
        <?php $link = get_author_posts_url($uID); ?>
        <?php $desc = strip_tags($meta['description'][0]); ?>
        <?php $short = (strlen($desc) > 110) ? substr($desc, 0, 107) .  '...' : $desc; ?>
        <div class="author-card">
            
            <div class="thumb-container">
            	<div class="thumb-title">
            		<h3><?php echo get_the_author_meta('display_name', $uID); ?></h3>
            	</div>
            	<?php echo get_avatar($uID, 250); ?>
            </div>
            <strong><?php echo get_the_author_meta('display_name', $uID); ?></strong> - 
            <div class="author-bio">
                <div class="short"><?php echo $short; ?></div>
                <?php if(strlen($desc) > 110): ?>
                <div class="full"><?php echo $desc; ?></div>
                <a href="#" class="readMore">Read More</a>
            	<?php endif; ?>
            	<?php if(is_single()): ?>
                <a href="<?php echo $link; ?>" class="btn buttonPrimary">View All by <?php echo get_the_author_meta('display_name', $uID); ?></a>
            	<?php endif; ?>
            </div>
        </div>
    <?php endif;
			}

			/*
			// -----------------------------------------------
			//  sharing()
			//  NEEDS DOCUMENTATION
			// -----------------------------------------------
			*/
			function sharing(){
			global $post;
			$title = urlencode(wp_title('|', false, 'right'));
			$url = urlencode(home_url() . $_SERVER['REQUEST_URI']);
			if( is_single() || is_page() ){
			if($post->post_excerpt){
			$desc = $post->post_excerpt;
			}else{
			$desc = strip_shortcodes(strip_tags($post->post_content));
			}
			if(strlen($desc) > 110){
			$desc = substr($desc, 0, 107) . "...";
			}
			} else{
			$desc = get_bloginfo('description');
			}
			$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
			if((is_single() || is_page()) && has_post_thumbnail($post->ID)){
			$img = wp_get_attachment_image_src( $post_thumbnail_id );
			}else{
			$img = get_template_directory_uri() . '/assets/img/logos/guideOutdoors.gif';
			}
 ?>
    <span class="share-buttons">
        <?php if(is_single() && comments_open( $post->ID )): ?>
        <a class="share-comment" href="#respond">Comment</a>
        <?php endif; ?>
        <a class="share-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $url; ?>&amp;t=<?php echo $title; ?>">Facebook</a>
        <a class="share-twitter" href="http://twitter.com/share?text=<?php echo $title; ?>&amp;url=<?php echo $url; ?>&amp;via=sportsmansguide">Twitter</a>
        <a class="share-gplus" href="https://plus.google.com/share?url=<?php echo $url; ?>">Google+</a>
        <a class="share-pin" href="http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&amp;media=<?php echo wp_get_attachment_image_src($post_thumbnail_id, 'large'); ?>&amp;description=<?php echo $desc; ?>">Pinterest</a>
        <a class="share-mail" href="mailto:?subject=<?php echo $title; ?>&amp;body=<?php echo $url; ?>%0D%0A%0D%0A<?php echo $desc; ?>">Email</a>
        <a class="share-print" href="javascript:window.print()">Print</a>
    </span>
    <?php }
	/*
	// -----------------------------------------------
	//  view_of_the_day()
	//  - Display the featured view of the day
	//  * This is used in the sidebar
	//
	// -----------------------------------------------
	*/

	function view_of_the_day(){
		$key = 'featured_general';
		$args = array(
    		'post_type' => array('view_of_day'),
    		'posts_per_page' => 1,
    		'meta_query' => array(
        		array(
            		'key' => $key,
            		'value' => 1,
        		)
    		),
            'orderby' => 'menu_order date',
            'order' => 'DESC'
		);
		$views = new WP_Query($args);
	    ?>
	    <?php if($views->post_count >=1): ?>
	    <div class="view-of-the-day widget">
	    	<h3>View of the Day</h3>
			<!--<?php echo (is_front_page()) ? 'Home' : 'Category';?>-->
	        <?php foreach($views->posts as $view): ?>
	    	<a href='<?php echo get_permalink($view -> ID); ?>'>
	    		<?php echo get_the_post_thumbnail($view -> ID, 'thumbnail-large'); ?>
	    	</a>
	    	<a href='<?php echo get_permalink($view -> ID); ?>'>
	    		<h4><?php echo $view -> view_location; ?></h4>
	    	</a>
	        <em>Submitted by <?php echo $view -> photographer_name; ?></em>
	        <?php endforeach; ?>
	        <a class="btn buttonSecondary" href="/share/view/">Submit Your Own</a>
	    </div>
	    <div class="clearfix"></div>
	    <?php endif; ?>
	<?php
	}
	/*
	// -----------------------------------------------
	//  featured_trophy()
	//  - Display the featured trophy
	//  * This is used in the sidebar
	//
	// -----------------------------------------------
	*/

	function featured_trophy(){
		$key = 'featured_general';
		$args = array(
		'post_type' => array('trophies'),
		'posts_per_page' => 1,
		'meta_query' => array(
		array(
		'key' => $key,
		'value' => 1,
		)
		)
		);
		$trophies = new WP_Query($args);
	    ?>
	    <?php if($trophies->post_count >=1): ?>
	    <div class="featured-trophy widget">
	    	<h3>Featured Trophy</h3>
			<!--<?php echo (is_front_page()) ? 'Home' : 'Category';?>-->
	        <?php foreach($trophies->posts as $trophy): ?>
	    	<a href='<?php echo get_permalink($trophy -> ID); ?>'>
	    		<?php echo get_the_post_thumbnail($trophy -> ID, 'thumbnail-large'); ?>
	    	</a>
	    	<a href='<?php echo get_permalink($view -> ID); ?>'>
	    		<h4><?php echo $trophy -> trophy_species; ?></h4>
	    	</a>
	        <em><?php echo $trophy -> trophy_names_picture; ?></em>
	        <?php endforeach; ?>
	        <a class="btn buttonSecondary" href="/share/trophy/">Submit Your Own</a>
	    </div>
	    <div class="clearfix"></div>
	    <?php endif; ?>
	<?php

	}
	/***********************************
	* MOVED TO THE spg_add_custom_types FUNCTION AT START OF CODE!
	* SEARCH FILTER
	* http://speckyboy.com/2010/09/19/10-useful-wordpress-search-code-snippets/
	*
	***********************************//*
	function SearchFilter($query) {
		if ($query->is_search or $query->is_feed) {
			// Gear
			if($_GET['post_type'] == "gear") {
				$query->set('post_type', array('gear'));
			}
			// Everything
			elseif($_GET['post_type'] == "all") {
				$query->set('post_type', array('adventures', 'tips', 'recipes', 'buyers_guides', 'news', 'gear', 'view_of_day'));
			}
		}
		return $query;
	}
	// This filter will jump into the loop and arrange our results before they're returned
	add_filter('pre_get_posts','SearchFilter');
*/

    /*
    // -----------------------------------------------
    //  spg_get_featured_types()
    //  Return an array of post types (names) that have the Featured checkbox enabled (ability to check a post as Featured)
    // -----------------------------------------------
    */
    // 
    function spg_get_featured_types(){
        $args = array(
            'post_type' => array('acf'),
            'name' => 'acf_feature-this-post',
            'posts_per_page' => -1
        );
        $acf = new WP_Query($args);
        $meta = get_post_meta($acf->posts[0]->ID);
        $rules = $meta['rule'];
        $post_type_arr = array();
        foreach($rules as $rule):
            $rule = unserialize($rule);
            if($rule['param'] == 'post_type'){
                $post_type_arr[] = $rule['value'];
            }
        endforeach;
        return $post_type_arr;
    }

	/*
	// -----------------------------------------------
	//  temp_scripts()
	//  TEMPORARY SCRIPTS/STYLES FOR DEVELOPMENT PURPOSES ONLY
	// -----------------------------------------------
	*/
	function temp_scripts() {
		wp_enqueue_style('temp_custom', get_template_directory_uri() . '/assets/css/matt-styles.css');
		wp_register_script('temp_roots_scripts', get_template_directory_uri() . '/assets/js/matt-scripts.js', array(), '0fc6af96786d8f267c8686338a34cd37', true);
		wp_enqueue_script('temp_roots_scripts');
	}
	add_action('wp_enqueue_scripts', 'temp_scripts', 200);
?>