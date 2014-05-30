<time class="published updated" datetime="<?php echo get_the_time('c'); ?>"><?php echo get_the_date(); ?></time>
<?php if(!is_singular('recipes') && !is_singular('view_of_day') && !is_singular('trophies') && !is_post_type_archive('recipes') && !is_post_type_archive('view_of_day') && !is_post_type_archive('trophies')): ?>
<p class="byline author vcard"><?php echo __('By', 'roots'); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a></p>
<?php endif; ?>