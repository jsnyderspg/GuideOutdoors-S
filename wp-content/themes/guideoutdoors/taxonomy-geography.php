<?php get_template_part('templates/page', 'header'); ?>
<?php $geo = get_queried_object(); ?>
<?php $post_type = $_REQUEST['type']; ?>
<?php if ( $post_type ) : ?>
    <?php get_template_part('templates/loop'); ?>
<?php else: ?>
    <?php carousel('adventures', 6, '', $geo->slug); ?>
    <?php author_carousel(); ?>
    <?php carousel('tips', 6, '', $geo->slug); ?>
    <?php /*carousel('trophies', 6, '', $geo->slug);*/ ?>
    <?php carousel('recipes', 6, '', $geo->slug); ?>
	<?php carousel('news', 6, '', $geo->slug); ?>
    <?php carousel('gear', 6, '', $geo->slug); ?>
    <?php carousel('post', 6, '', $geo->slug); ?>

<?php endif; // end post type check ?>