<?php
header('Content-Type: application/json');
require("../../../../wp-load.php"); 
global $wp_query;
$paged = ( $_REQUEST['paged'] ) ? intval($_REQUEST['paged']) : 1;
$post_type = ( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : array('post');
$posts_per_page = ( $_REQUEST['posts_per_page'] ) ? intval($_REQUEST['posts_per_page']) : 6;
$cat = ( $_REQUEST['cat'] ) ? $_REQUEST['cat'] : "";
$geo = ( $_REQUEST['geo'] ) ? $_REQUEST['geo'] : "";
$season = ( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : array('post');
$args = array(
    'post_type' => $post_type,
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
);
if($cat){
    $args['tax_query'][] = array(
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => array( $cat )
    );
}
if($geo){
    $args['tax_query'][] = array(
        'taxonomy' => 'geography',
        'field' => 'slug',
        'terms' => array( $geo )
    );
}
$wp_query = new WP_Query($args);
if($wp_query->post_count >= 1){
    foreach($wp_query->posts as $p){
        unset($p->post_content);
        $p->post_excerpt = htmlspecialchars($p->post_excerpt, ENT_QUOTES);
        $p->post_thumbnail = "";
        $p->permalink = get_permalink($p->ID);
        $p_thumb_id = get_post_thumbnail_id( $p->ID );
        if($p_thumb_id){
            $p_thumb = wp_get_attachment_image_src( $p_thumb_id );
            $p->post_thumbnail = $p_thumb[0];
        }
    }
    $json = new stdClass();
    $json->max_num_pages = $wp_query->max_num_pages;
    $json->current_page = $paged;
    $json->posts = $wp_query->posts;
    echo json_encode($json);
}
?>
