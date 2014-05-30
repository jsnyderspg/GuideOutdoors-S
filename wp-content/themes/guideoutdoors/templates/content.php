<article <?php post_class('col-sm-4'); ?>>
  <a href="<?php the_permalink(); ?>">
  	<div class="thumb-container">
		<div class="thumb-title">
			<h3><?php the_title(); ?></h3>
		</div>
		<?php if(has_post_thumbnail()): ?>
			<?php the_post_thumbnail('thumbnail'); ?>
    <?php else: ?>
      <img src="/wp-content/uploads/TSG_placeholder_image.jpg" />
		<?php endif; ?>
		
  	</div>
  </a>
  <div class="entry-summary">
    <?php /*<span class='date'><?php the_date('M j, Y'); ?> -</span>*/ ?>
    <span class='description'><?php the_excerpt(); ?></span>
    <a href='<?php the_permalink(); ?>' class="readMore">READ MORE &rsaquo;</a>
  </div>
</article>