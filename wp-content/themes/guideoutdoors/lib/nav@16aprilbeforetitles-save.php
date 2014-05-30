<?php
/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * Roots_Nav_Walker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 */
ini_set('max_execution_time', 0);
class Roots_Nav_Walker extends Walker_Nav_Menu {

  function check_current($classes) {
    return preg_match('/(current[-_])|active|dropdown/', $classes);
  }

  function start_lvl(&$output, $depth = 0, $args = array()) {
		$output .= "\n<ul class=\"dropdown-menu\" id=\"menu2\">\n";
  }
  function getPostTypeURLs(){
    $post_types = get_post_types( array('public'=>true),'names');
    $pts = array();
    foreach($post_types as $k => $v){
      if($v = get_post_type_archive_link($v)){
        $pts[$k] = $v;
      }
    }
    return $pts;
  }
  function getFeaturedPost($cat,$post_type = "") // Getting featured post from parent category
  {
	  global $post;

    $args = array(
			'orderby' => 'post_date',
			'meta_key' => 'featured_general', // the name of the custom field
			'meta_compare' => '=',
			'meta_value' => 1,
			'posts_per_page' => 1,
			'orderby' => 'post_date',
			'order' => 'DESC',
      'post_type' => array('post','recipes','tips','adventures','gear','news','buyers_guides')
    );
    if($cat && $cat != ''){
      $args['cat'] = $cat;
    }
    if($post_type && $post_type != ''){
      $args['post_type'] = $post_type;
    }
	  $loop = new WP_Query($args);

		$featured = '';

		$featured .= '<span class="featured-title">Featured</span>';

	   if($loop->have_posts())
	   {
		   while( $loop->have_posts() ) : $loop->the_post();  //Loog throught all posts

		   		$featured .= '<div class="thumb-container" style="position:relative;">';
		   		
		   		$featured .= '<div class="thumb-title"><h3><a href="'.get_permalink($post->post_ID).'">'.$post->post_title.'</a></h3></div>';

				if ( has_post_thumbnail() ) //Check if featured post has thumbnail image
				{
					$featured .= '<a href="'.get_permalink($post->post_ID).'">'.get_the_post_thumbnail($post->ID,'thumbnail-large').'</a>';
				}
				else
				{
					$featured .= '<img src="'.home_url().'/wp-content/uploads/TSG_placeholder_image.jpg" />';
				}
				$featured .= '</div>';

				$featured .= '<div class="featured-content">';

				$featured .= '<p><strong>'.date('M j, Y', strtotime($post->post_date)).'</strong> - ' . strip_tags(substr($post->post_content, 0, 100)) . '...</p> <a href="'.$post->guid.'">Read More</a>';

				$featured .= '</div>';

			endwhile;
	   }
	   else
	   {
		   $featured .= '<p></p>';
	   }


		wp_reset_query();

	 	return $featured;
  }

  function getRecentPosts($cat, $post_type = "") // Getting recent posts from tips and adventures
  {
	  global $post;

    $args = array(
			'post_type' => array( 'tips', 'adventures' ),
			'orderby' => 'post_date',
			'posts_per_page' => 4,
			'orderby' => 'post_date',
			'order' => 'DESC',
    );

		if($cat && $cat != ''){
      $args['cat'] = $cat;
    }
    if($post_type && $post_type != ''){
      $args['post_type'] = $post_type;
    }
	  $loop = new WP_Query($args);
	   $tips = '';

	   $tips .= '<span class="tips_posts featured-title">Recent Tips & Adventures</span>';

	   $tips .= '<table class="tips">';

	   while( $loop->have_posts() ) : $loop->the_post();  //Loog throught all posts

	   $tips .= '<tr><td style="text-align:center;">';
	   				if ( has_post_thumbnail() ) //Check if featured post has thumbnail image
				{
					$tips .= get_the_post_thumbnail($post->ID,array(62, 62));
				}
				else
				{
					$tips .= '<img src="'.home_url().'/wp-content/uploads/TSG_placeholder_image.jpg" width="62px" height="62px" />';
				}
	  $tips .='</td>';

	   $tips .= '<td class="tips_info"><span class="recent_post_title"><a href="'.post_permalink( $post->ID ).'">'.$post->post_title.'</a></span><br />
	   			<span class="tips_author_info">By '.get_the_author().' ' . date("m/d/y", strtotime($post->post_date)).'</span></td></tr>';

	   endwhile;

	   $tips .= '</table>';

	   $tips .= '<a href="'.get_category_link( $cat ).'" class="view-all-cat">View All</a>';

		wp_reset_query();

	 	return $tips;
  }

  function getUserPosts($type, $limit,$str) // Getting user info w.r.t to post/posty types.
  {
	  global $wpdb;
	  $result = '';
	  if($type[0] == 'tips' || $type[0] == 'adventures')
	  {
		  $result .= '<span class="expert-heading">Guide Outdoors Experts</span>';
	  }
	  else
	  {
		  $result .= '<span class="expert-heading">Bloggers</span>';
	  }
    $type = implode("' OR post_type = '", $type);
	if($str == "experts"){
		$query = "SELECT  post_author, display_name, u.ID, m.meta_key,m.meta_value
				FROM wp_posts p, wp_users u, wp_usermeta m,wp_usermeta m1 WHERE 1=1 AND (u.ID = p.post_author) AND ((m.user_id = u.ID)) AND ((m1.user_id = u.ID))
				AND (post_type = '$type') AND (post_status = 'publish' OR post_status = 'private') AND ((m.meta_key='user_sorting_order_number')) AND ((m1.meta_key='user_featured_expert') AND (m1.meta_value='true'))
				GROUP BY post_author Order by m.meta_value ASC LIMIT 0,$limit";
		}
	if($str == "blogger"){
		$query = "SELECT  post_author, display_name, u.ID, m.meta_key,m.meta_value
				FROM wp_posts p, wp_users u, wp_usermeta m,wp_usermeta m1 WHERE 1=1 AND (u.ID = p.post_author) AND ((m.user_id = u.ID)) AND ((m1.user_id = u.ID))
				AND (post_type = '$type') AND (post_status = 'publish' OR post_status = 'private') AND ((m.meta_key='user_sorting_order_number')) AND ((m1.meta_key='user_featured_blogger') AND (m1.meta_value='true'))
				GROUP BY post_author Order by m.meta_value ASC LIMIT 0,$limit";;
		}
	$query_res = mysql_query($query);
	$count = 1;
	  $result .= '<ul class="expert-ul">';
	  while($rec = mysql_fetch_array($query_res))
	  {
		$author_url = '<a href="'. site_url().'/?author='.$rec['ID'].'">'.$rec['display_name'].'</a>';
		$result .= '<li><table class="expert-table"><tr><td>'.get_avatar($rec['post_author'], 38).'</td><td class="link-expert">'.$author_url.'<br />'.get_user_meta( $rec['ID'], 'user_blog_name',true).'</td></tr></table></li>';
	  if($count == 6)
	  {
		  $result .= '</ul><ul class="expert-ul expert-ul2">';
	  }
	  $count++;
	  }
	  $result .= '</ul>';
	return $result;
  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    $item_html = '';
    parent::start_el($item_html, $item, $depth, $args);
    $post_type_archives = $this->getPostTypeURLs();
    if ($item->is_dropdown && ($depth === 0)) {
      if ($args->theme_location == 'primary_navigation' && $item->type == 'custom')
      { //if item is experts
        if($item->title == 'Experts')
        {
            $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-target="#"', $item_html);
            $item_html = str_replace('</a>', ' <b class="caret"></b></a>
            <div class="outdoor-experts-outer">'.$this->getUserPosts(array('tips','adventures'), 12,"experts").'</div>
            <div class="blogger-experts-outer">'.$this->getUserPosts(array('post'), 6,"blogger").'</div>', $item_html);
        }
        else //for all other custom menu items
        {
            $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-target="#"', $item_html);
            $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
        }
      }
      else //for all other menu items
      {
             $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-target="#"', $item_html);
            $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
      }

    }
    elseif (stristr($item_html, 'li class="divider')) {
      $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
    }
    elseif (stristr($item_html, 'li class="dropdown-header')) {
      $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
    }elseif($item->object == 'category' || $is_post_type_archive = in_array($item->url, $post_type_archives)){
        $featured = ($is_post_type_archive) ? $this->getFeaturedPost('',array_search( $item->url, $post_type_archives)) : $this->getFeaturedPost($item->object_id);
        $recent = ($is_post_type_archive) ? $this->getRecentPosts('',array_search( $item->url, $post_type_archives)) : $this->getRecentPosts($item->object_id);
//        $mega_class = ($is_post_type_archive) ? " mega-left" : "";
        $item_html = (!$is_post_type_archive) ? str_replace('"><a', ' category-menu"><a', $item_html) : $item_html;
        $item_html = str_replace('</a>', '<b class="abc li_abc" style="float:right;"></b></a>
        <div class="mega-outer">
        <div class="featured-outer">'.$featured.'</div>
        <div class="recent-posts">'.$recent.'</div>
        </div>', $item_html);
    }
    $item_html = apply_filters('roots_wp_nav_menu_item', $item_html);
    $output .= $item_html;
  }

  function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
    $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

    if ($element->is_dropdown) {
      $element->classes[] = 'dropdown';
    }

    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
}

/**
 * Remove the id="" on nav menu items
 * Return 'menu-slug' for nav menu classes
 */
function roots_nav_menu_css_class($classes, $item) {
  $slug = sanitize_title($item->title);
  $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
  $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

  $classes[] = 'menu-' . $slug;

  $classes = array_unique($classes);

  return array_filter($classes, 'is_element_empty');
}
add_filter('nav_menu_css_class', 'roots_nav_menu_css_class', 10, 2);
//add_filter('nav_menu_item_id', '__return_null');

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use Roots_Nav_Walker() by default
 */
function roots_nav_menu_args($args = '') {
//	if($args['theme_location'] == 'primary_navigation') // Remove all other so that only primary navigation will get effected
//	{
	  $roots_nav_menu_args['container'] = false;

	  if (!$args['items_wrap']) {
		$roots_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
	  }

	  if (current_theme_supports('bootstrap-top-navbar') && !$args['depth']) {
		$roots_nav_menu_args['depth'] = 5;
	  }

	  if (!$args['walker']) {
		$roots_nav_menu_args['walker'] = new Roots_Nav_Walker();
	  }

	  return array_merge($args, $roots_nav_menu_args);
	}

//}
add_filter('wp_nav_menu_args', 'roots_nav_menu_args');