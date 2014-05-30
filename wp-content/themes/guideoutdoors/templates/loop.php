<?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'roots'); ?>
    </div>
    <?php get_search_form(); ?>
<?php else: ?>
    <div class="row">
    <?php $i = 0; ?>
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/content', get_post_format()); ?>
        <?php
            $i++;
            if($i == 3){
                echo '</div><div class="row">';
                $i = 0;
            }
        ?>
    <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
    <?php
        function paginate() {

            global $wp_query, $wp_rewrite;
            $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
            
            $pagination = array(
                'base' => @add_query_arg('page','%#%'),
                'format' => '',
                'total' => $wp_query->max_num_pages,
                'current' => $current,
                'show_all' => false,
                'type' => 'list',
                'prev_next' => false
                );
            $path = explode('?',get_pagenum_link( 1 ));
            
            if( $wp_rewrite->using_permalinks() )
                $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', $path[0] ) ) . 'page/%#%/', 'paged' );
            
            if( !empty($wp_query->query_vars['s']) )
                $pagination['add_args'] = array( 's' => urlencode(get_query_var( 's' )) );

            if( !empty($_GET['post_type']) )
                $pagination['add_args']['post_type'] = urlencode($_GET['post_type']);

            if( !empty($_GET['type']) )
                $pagination['add_args']['type'] = urlencode($_GET['type']);
            
            echo str_replace("ul class='page-numbers", "ul class='pagination", paginate_links( $pagination ) );
        }
    ?>
    <?php paginate(); ?>
<?php endif; ?>