<?php get_template_part('templates/page', 'header'); ?>
<?php
    $cat = get_queried_object();
    $children = array(); 
    $post_type = $_REQUEST['type'];
    if(!$cat->category_parent){
        $children = apply_filters( 
                        'taxonomy-images-get-terms', 
                        '', 
                        array(
                            'having_images' => false,
                            'term_args'     => array( 'parent' => $cat->term_id )
                        )
                    );
    }
?>
<?php // Is this a child category, or a top-level category without children? ?>
<?php if ( $cat->category_parent || !$children[0] ) : ?>
    <?php // Has the user clicked the "View All" button to see all posts of a certain type in this category? ?>
    <?php if ( $post_type ) : ?>
        <?php get_template_part('templates/loop'); ?>
    <?php else: ?>
        <?php carousel('adventures', 6, $cat->slug); ?>
        <?php carousel('tips', 6, $cat->slug); ?>
        <?php carousel('recipes', 6, $cat->slug); ?>
        <?php carousel('news', 6, $cat->slug); ?>
        <?php carousel('gear', 6, $cat->slug); ?>
        <?php carousel('post', 6, $cat->slug); ?>
        <?php author_carousel(); ?>
    <?php endif; // end post type check ?>
<?php // This is a top-level category with children. Display all children categories. ?>
<?php else: ?>
    <?php $i = 0; ?>
    <div class="row">
    <?php foreach($children as $child): ?>
        <div class="col-sm-4">
            <div class="thumb-container">
                <div class="thumb-title"><h3><a href="<?php echo $child->slug; ?>"><?php echo $child->name; ?></a></h3></div>
                <a href="<?php echo $child->slug; ?>"><?php echo (wp_get_attachment_image( $child->image_id, 'thumbnail' )) ? wp_get_attachment_image( $child->image_id, 'thumbnail' ) : '<img src="/wp-content/uploads/TSG_placeholder_image.jpg" />'; ?></a>
                <?php $i++; ?>
            </div>
        </div>
        <?php if($i == 3): ?>
    </div>
    <div class="row">
            <?php $i = 0; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endif; // end category descendent check ?>