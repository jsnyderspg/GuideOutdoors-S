<?php
/**
 * Register sidebars and widgets
 */
function roots_widgets_init() {
  // Sidebars
  register_sidebar(array(
    'name'          => __('Primary', 'roots'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ));

  register_sidebar(array(
    'name'          => __('Footer', 'roots'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ));

  // Widgets
  register_widget('Guide_Outdoors_Social_Widget');
  register_widget('Guide_Outdoors_State_Widget');
  register_widget('Guide_Outdoors_Author_Widget');
  register_widget('Guide_Outdoors_Spiff_Widget');
  register_widget('Guide_Outdoors_View_Widget');
  register_widget('Guide_Outdoors_Trophy_Widget');
  register_widget('Guide_Outdoors_Recipe_Cats_Widget');
}
add_action('widgets_init', 'roots_widgets_init');

/**
 * Social Sharing Widget
 */
class Guide_Outdoors_Social_Widget extends WP_Widget {
  private $fields = array(

  );

  function __construct() {
    $widget_ops = array('classname' => 'widget_guideoutdoors_social', 'description' => __('Use this widget to add links to the Sportsman\'s Guide social media pages to the sidebar.', 'roots'));

    $this->WP_Widget('widget_guideoutdoors_social', __('Social Media Icons', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_guideoutdoors_social';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    $cache = wp_cache_get('widget_guideoutdoors_social', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);

    if(is_front_page()):
    echo $before_widget; 
    ?>
      <a href="https://www.facebook.com/sportsmansguide"> <img src="<?php echo home_url(); ?>/wp-content/themes/guideoutdoors/assets/img/icons/facebook.gif"> </a>
      <a href="https://twitter.com/sportsmansguide"> <img src="<?php echo home_url(); ?>/wp-content/themes/guideoutdoors/assets/img/icons/twitter.gif"> </a>
      <a href="https://www.youtube.com/user/sportsmansguide"> <img src="<?php echo home_url(); ?>/wp-content/themes/guideoutdoors/assets/img/icons/youtube.gif"> </a>
      <a href="https://plus.google.com/+sportsmansguide"> <img src="<?php echo home_url(); ?>/wp-content/themes/guideoutdoors/assets/img/icons/googleplus.gif"> </a>
      <a href="http://www.pinterest.com/sportsmansguide/"> <img src="<?php echo home_url(); ?>/wp-content/themes/guideoutdoors/assets/img/icons/pinterest.gif"> </a>
    <?php 
    echo $after_widget;
    endif;
    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_guideoutdoors_social', $cache, 'widget');
  }

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['widget_guideoutdoors_social'])) {
      delete_option('widget_guideoutdoors_social');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_guideoutdoors_social', 'widget');
  }

  function form($instance) {
    ?>
    <p>Use this widget to add the sharing icons to the sidebar (comment, facebook, email, print, etc).</p>
    <?php
  }
}
/**
 * State Select Widget
 */
class Guide_Outdoors_State_Widget extends WP_Widget {
  private $fields = array(

  );

  function __construct() {
    $widget_ops = array('classname' => 'widget_guideoutdoors_states', 'description' => __('Use this widget to add the state selection dropdown to the sidebar.', 'roots'));

    $this->WP_Widget('widget_guideoutdoors_states', __('State Selection', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_guideoutdoors_states';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    $cache = wp_cache_get('widget_guideoutdoors_states', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);

    echo $before_widget;
    state_select();
    echo $after_widget;

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_guideoutdoors_states', $cache, 'widget');
  }

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['widget_guideoutdoors_states'])) {
      delete_option('widget_guideoutdoors_states');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_guideoutdoors_states', 'widget');
  }

  function form($instance) {
    ?>
    <p>Use this widget to add the state selection dropdown to the sidebar.</p>
    <?php
  }
}
/**
 * Author Widget
 */
class Guide_Outdoors_Author_Widget extends WP_Widget {
  private $fields = array(

  );

  function __construct() {
    $widget_ops = array('classname' => 'widget_guideoutdoors_author', 'description' => __('Use this widget to add the current post\'s author info to the sidebar.', 'roots'));

    $this->WP_Widget('widget_guideoutdoors_author', __('Author Card', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_guideoutdoors_author';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
  /*  $cache = wp_cache_get('widget_guideoutdoors_author', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);

    if(!is_singular('recipes') && !is_singular('view_of_day') && !is_singular('trophies') && !is_post_type_archive('recipes') && !is_post_type_archive('view_of_day') && !is_post_type_archive('trophies')):
    */  echo $before_widget;
      author_card();
      echo $after_widget; /*
    endif;

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_guideoutdoors_author', $cache, 'widget'); */
  } 

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['widget_guideoutdoors_author'])) {
      delete_option('widget_guideoutdoors_author');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_guideoutdoors_author', 'widget');
  }

  function form($instance) {
    ?>
    <p>Display an author's image and info on <strong>individual post or author archive pages</strong>. If on an author archive, the author's bio will display in it's entirety.</p>
    <?php
  }
}
/**
 * Spiff Widget
 */
class Guide_Outdoors_Spiff_Widget extends WP_Widget {
  private $fields = array(

  );

  function __construct() {
    $widget_ops = array('classname' => 'widget_guideoutdoors_spiffs', 'description' => __('Use this widget to add Spiffs to the sidebar.', 'roots'));

    $this->WP_Widget('widget_guideoutdoors_spiffs', __('Spiffs', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_guideoutdoors_spiffs';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    /*
    $cache = wp_cache_get('widget_guideoutdoors_spiffs', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);

*/
    echo $before_widget;
    spiffs();
    echo $after_widget;
/*
    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_guideoutdoors_spiffs', $cache, 'widget');*/
  }

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['widget_guideoutdoors_spiffs'])) {
      delete_option('widget_guideoutdoors_spiffs');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_guideoutdoors_spiffs', 'widget');
  }

  function form($instance) {
    ?>
    <p>All Spiffs will appear wherever you place this widget. Use the options on a Spiff to filter on which pages it will appear. Use the Menu Order field to sort the Spiffs within this widget.</p>
    <?php
  }
}
/**
 * View of the Day
 */
class Guide_Outdoors_View_Widget extends WP_Widget {
  private $fields = array(

  );

  function __construct() {
    $widget_ops = array('classname' => 'widget_guideoutdoors_view', 'description' => __('Use this widget to add a View of the Day to the sidebar.', 'roots'));

    $this->WP_Widget('widget_guideoutdoors_view', __('View of the Day', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_guideoutdoors_view';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    $cache = wp_cache_get('widget_guideoutdoors_view', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);


    echo $before_widget;
    view_of_the_day();
    echo $after_widget;

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_guideoutdoors_view', $cache, 'widget');
  }

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['widget_guideoutdoors_view'])) {
      delete_option('widget_guideoutdoors_view');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_guideoutdoors_view', 'widget');
  }

  function form($instance) {
    ?>
    <p>Use this widget to add a View of the Day to the sidebar.</p>
    <?php
  }
}
/**
 * Featured Trophy
 */
class Guide_Outdoors_Trophy_Widget extends WP_Widget {
  private $fields = array(

  );

  function __construct() {
    $widget_ops = array('classname' => 'widget_guideoutdoors_trophy', 'description' => __('Use this widget to add a Featured Trophy to the sidebar.', 'roots'));

    $this->WP_Widget('widget_guideoutdoors_trophy', __('Featured Trophy', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_guideoutdoors_trophy';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    $cache = wp_cache_get('widget_guideoutdoors_trophy', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);


    echo $before_widget;
    featured_trophy();
    echo $after_widget;

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_guideoutdoors_trophy', $cache, 'widget');
  }

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['widget_guideoutdoors_trophy'])) {
      delete_option('widget_guideoutdoors_trophy');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_guideoutdoors_trophy', 'widget');
  }

  function form($instance) {
    ?>
    <p>Use this widget to add a Featured Trophy to the sidebar.</p>
    <?php
  }
}
/**
 * Recipe Categories
 */
class Guide_Outdoors_Recipe_Cats_Widget extends WP_Widget {
  private $fields = array(

  );

  function __construct() {
    $widget_ops = array('classname' => 'widget_guideoutdoors_recipe_cats', 'description' => __('Use this widget to add a list of Food Categories to the sidebar for Recipe archives and pages.', 'roots'));

    $this->WP_Widget('widget_guideoutdoors_recipe_cats', __('Food Categories', 'roots'), $widget_ops);
    $this->alt_option_name = 'widget_guideoutdoors_recipe_cats';

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
/*    $cache = wp_cache_get('widget_guideoutdoors_recipe_cats', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);*/


    if(is_post_type_archive('recipes') || is_singular('recipes') || is_tax('food_categories')):
      echo $before_widget; ?>
        <h3>Food Categories</h3>

        <ul class="recipe-cats">
          <?php 
            $categories = get_categories(array('taxonomy' => 'food_categories')); 
            $i = 0;
            foreach ($categories as $category):
          ?>
          <?php echo ($i == 10) ? '</ul><ul class="collapse collapsed recipe-cats">' : ''; ?>
          <li>
            <a href="<?php echo home_url().'/food/'.$category->category_nicename; ?>">
              <?php echo $category->cat_name; ?>
              (<?php echo $category->category_count; ?>)
            </a>
          </li>
          <?php $i++; endforeach; ?>
        </ul>
        <a class="btn btn-sm buttonPrimary more-recipe-cats" data-toggle="collapse" data-target=".collapse.recipe-cats">View more food categories</a>
    <?php 
      echo $after_widget;
    endif; 
/*
    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('widget_guideoutdoors_recipe_cats', $cache, 'widget');
	*/
  }

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['widget_guideoutdoors_recipe_cats'])) {
      delete_option('widget_guideoutdoors_recipe_cats');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('widget_guideoutdoors_recipe_cats', 'widget');
  }

  function form($instance) {
    ?>
    <p>Use this widget to add Food Categories to the sidebar.</p>
    <?php
  }
}