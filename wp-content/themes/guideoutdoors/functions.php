<?php
/**
 * Roots includes
 */
require_once locate_template('/lib/utils.php');           // Utility functions
require_once locate_template('/lib/init.php');            // Initial theme setup and constants
require_once locate_template('/lib/wrapper.php');         // Theme wrapper class
require_once locate_template('/lib/sidebar.php');         // Sidebar class
require_once locate_template('/lib/config.php');          // Configuration
require_once locate_template('/lib/activation.php');      // Theme activation
require_once locate_template('/lib/titles.php');          // Page titles
require_once locate_template('/lib/cleanup.php');         // Cleanup
require_once locate_template('/lib/nav.php');             // Custom nav modifications
require_once locate_template('/lib/gallery.php');         // Custom [gallery] modifications
require_once locate_template('/lib/comments.php');        // Custom comments modifications
require_once locate_template('/lib/relative-urls.php');   // Root relative URLs
require_once locate_template('/lib/widgets.php');         // Sidebars and widgets
require_once locate_template('/lib/scripts.php');         // Scripts and stylesheets
require_once locate_template('/lib/custom.php');          // Custom functions

/** Custom profile fields - add these to their own PHP file  */

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
add_filter('wpseo_title', 'filter_type_wpseo_title'); /* add the post type at the beginning of the title */

function my_show_extra_profile_fields( $user ) { ?>

<h3>Extra profile information</h3>
<table class="form-table">
	<tr>
		<th><label>Featured Expert</label></th>
		<td>
			<input type="checkbox" name="user_featured_expert" id="user_featured_expert" value="true" <?php if (esc_attr( get_the_author_meta( "user_featured_expert", $user->ID )) == "true") echo "checked"; ?> />
			<label for="user_featured_expert">Featured Author</label>
		</td>
	</tr>
		<tr>
		<th><label>Featured Blogger</label></th>
		<td>
			<input type="checkbox" name="user_featured_blogger" id="user_featured_blogger" value="true" <?php if (esc_attr( get_the_author_meta( "user_featured_blogger", $user->ID )) == "true") echo "checked"; ?> />
			<label for="user_featured_blogger">Featured Blogger</label>
		</td>
	</tr>
	<tr>
		<th><label>Blog Name</label></th>
		<td>
			<input type="text" name="user_blog_name" id="user_blog_name" value="<?php echo esc_attr( get_the_author_meta( 'user_blog_name', $user->ID ) ); ?>" class="regular-text" /><br />
			<label for="user_blog_name">Blog Name</label>
		</td>
	</tr>
	<tr>
		<th><label>Order</label></th>
		<td>
			<input type="text" name="user_sorting_order_number" id="user_sorting_order_number" value="<?php echo esc_attr( get_the_author_meta( 'user_sorting_order_number', $user->ID ) ); ?>" class="regular-text" /><br />
			<label for="user_blog_name">Sorting Rank</label>
		</td>
	</tr>
</table>
<?php }
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

update_user_meta( $user_id, 'user_featured_blogger', $_POST['user_featured_blogger'] );
update_user_meta( $user_id, 'user_featured_expert', $_POST['user_featured_expert'] );
update_user_meta( $user_id, 'user_blog_name', $_POST['user_blog_name'] );
update_user_meta( $user_id, 'user_sorting_order_number', $_POST['user_sorting_order_number'] );
}

/* proof-of-concept shortcode to output the featured experts name and blog name */
add_shortcode( 'list-featured-experts', 'featured_experts_shortcode' );

function featured_experts_shortcode($atts)
{
    ob_start();
    $query = array('meta_key' => 'user_featured_expert', 'meta_value' => 'true');

    $user_query = new WP_User_Query($query);

// User Loop
    if (!empty($user_query->results)) {
        foreach ($user_query->results as $user) {
		 /* $author_url = '<a href="'. site_url().'/?author='.$rec['ID'].'">'.$rec['display_name'].'</a>';*/
           echo '<p>' . $user->display_name . '</p>'; 
		   echo $user->user_blog_name;
		   echo $user->user_sorting_order_number;
		  
        }
    } else {
        echo 'No users found.';
    }
    $myvariable = ob_get_clean();
    return $myvariable;
}
//disable WordPress sanitization to allow more than just $allowedtags from /wp-includes/kses.php
remove_filter('pre_user_description', 'wp_filter_kses');
//add sanitization for WordPress posts
add_filter( 'pre_user_description', 'wp_filter_post_kses');

function get_flyout_menu($cat_id) {
	$cat_id = $_GET['cat_id'];
	if(!$cat_id) die(0);
    /** supply from cache **/
    $page_from_cache = get_option( "flyout_menu{$cat_id}", 0 );
    if($page_from_cache){
        wp_send_json($page_from_cache);
    }
    /** cache end **/
	// 1. Fetch the menu (we'll assume it has an id of 2)...
	$menu = wp_get_nav_menu_object(838);
	// 2. Create an empty $menu_items array
	$menu_items = array();
	// 3. Get menu objects (this is our tree structure)
	if ( $menu && ! is_wp_error($menu) ) {
		$menu_items = wp_get_nav_menu_items( $menu );
	}
	// 4. Create a new instance of our walker...
	$walk = new Roots_Nav_Walker();
	$post_type_archives = $walk->getPostTypeURLs();
		$return = array(
			'ID'		=> $cat_id
		);
	foreach ( (array) $menu_items as $key => $menu_item ) {
	    if ($menu_item->ID == $cat_id) {
			$is_post_type_archive = in_array($menu_item->url, $post_type_archives);
        	$return['featured'] = ($is_post_type_archive) ? $walk->getFeaturedPost('',array_search( $menu_item->url, $post_type_archives)) : iconv("utf-8", "utf-8//ignore", $walk->getFeaturedPost($menu_item->object_id));
                $return['recent'] = ($is_post_type_archive) ? $walk->getRecentPosts('',array_search( $menu_item->url, $post_type_archives)) : iconv("utf-8", "utf-8//ignore", $walk->getRecentPosts($menu_item->object_id));
			break;
		}
	}
	$new_cache = $return;
    /** save the cache **/
    update_option( "flyout_menu{$cat_id}",$new_cache);
    /** end cache **/
    //die($new_cache);
	wp_send_json($new_cache);
}
add_action("save_post","invalidate_cache");
function invalidate_cache(){
	$menu = wp_get_nav_menu_object(838);
	$menu_items = array();
	if ( $menu && ! is_wp_error($menu) ) {
		$menu_items = wp_get_nav_menu_items( $menu );
	}
	foreach ( (array) $menu_items as $key => $menu_item ) {
        delete_option( "flyout_menu{$menu_item->ID}" );
    	}
}
add_action('wp_ajax_get_flyout_menu', 'get_flyout_menu');
add_action('wp_ajax_nopriv_get_flyout_menu', 'get_flyout_menu');
add_action('wp_head','ajaxurl');
function ajaxurl() { ?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
var ajaxurl_cache = '<?php echo admin_url('admin-ajax-cache.php'); ?>';
</script>
<?php } ?>
<?
function filter_type_wpseo_title($title) {
    if( isset($_GET["type"]) ){ 
	$ptype = $_GET["type"]; 
	$newtitle = ucfirst($ptype . " | " . $title);
	return $newtitle;
	} else { return $title; }
	
}?>