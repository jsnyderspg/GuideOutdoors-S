<div class="page-header">
<? $type = $_GET["type"]; ?>
  <h1>
    <?php /*echo roots_title(); */ ?>
	<?php  

$queried_object = get_queried_object();
$taxonomy = $queried_object->category;
$term_id = $queried_object->category_id;
$cat_id = $wp_query->get_queried_object_id();
/* $subheading = get_field('seo_h1', $taxonomy . '_' . $term_id);*/
$categories = get_category_parents($cat_id);
$h1 = get_field('seo_h1', 'category_'. $cat_id);


/* if not an archive, just use the standard title */
if(!is_archive() ||
is_post_type_archive('trophies') ||
 is_post_type_archive('view_of_day') || 
 is_post_type_archive('gear') || is_post_type_archive('news') || is_author() ||
 is_post_type_archive('recipes')) $page_h1 = roots_title(); 
 else {
	/* is our parent hunting, fishing or explore? */
	$cat_fishing = strpos($categories,'Fishing');
	$cat_hunting = strpos($categories,'Hunting');
	$cat_explore = strpos($categories,'Explore');
	 /* 
		1. Check for a parameter. If it exists add it to the title.
		2. Check for a custom h1
		3. Use the default title 
	 */

	if( isset($_GET["type"]) ){ 
		$ptype = $_GET["type"]; 
		/* build out a new title since this is a filtered view */
		$page_h1 = ucfirst($ptype . ": " . roots_title());
		/*
		if (strlen($h1) > 0) { 
			$page_h1 = ucfirst($ptype . ": " . $h1);
		} 	else { $page_h1 = ucfirst ($ptype . ": " . roots_title());	}
		*/
	}
	/* elseif(strlen($h1) > 0) { $page_h1 = $h1; }*/
	else { 
		/* build a custom seo-friendly title - Whitetail Hunting Tips, News, Adventures, Gear & Blog Posts */
		/* $page_h1 = roots_title(); */
		if($cat_fishing > -1 || $cat_hunting > -1 || $cat_explore > -1) {
		$page_h1 = roots_title() . " Tips, News, Adventures, Gear Reviews & Blog Posts";
		}
		else $page_h1 = roots_title();
	}
}
echo $page_h1;

?>
 
</h1>
 <?php 
 /* Hide the top abstract if on a filtered page */
 if( !isset($_GET["type"]) ){ 
	$top_abstract = get_field('seo_top_abstract', 'category_'. $cat_id);
	echo $top_abstract;
 }
 ?>
</div>
