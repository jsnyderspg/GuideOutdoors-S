<?php
/*
Template Name: Experts Template
*/
?>
<?php get_template_part('templates/page', 'header'); ?>
<?php
 $query = "SELECT post_author, COUNT(*) as post_count, display_name, u.ID,m.meta_key,m.meta_value
			FROM wp_posts p JOIN wp_users u ON (u.ID = p.post_author) JOIN wp_usermeta m ON ((m.user_id = u.ID))
			WHERE (post_type = 'tips' OR post_type = 'adventure') AND (post_status = 'publish' OR post_status = 'private') AND ((m.meta_key='user_featured_expert') AND (m.meta_value='true'))
			GROUP BY post_author ORDER BY post_count DESC";
			
	$query_res = mysql_query($query);


?>

<div class="row" style="margin-top:5px; margin-bottom:5px;">
            <?php
		$count = 0;
	  while($rec = mysql_fetch_array($query_res))
	  {
		  echo '
		  <div class="col-sm-4" style="margin-top:5px; margin-bottom:5px;">
            <div class="thumb-container">
                <div class="thumb-title"><h3><a href="'. site_url().'/?author='.$rec['ID'].'">'.$rec['display_name'].'</a></h3></div>';
				
				echo '<a href="'. site_url().'/?author='.$rec['ID'].'">'.get_avatar($rec['post_author'], 150).'</a>';
				
                $count++; 
				
				echo '</div></div>';
				if($count == 3)
				{
					echo '</div><div class="row"  style="margin-top:5px; margin-bottom:5px;">';
					$count = 0;	
				}
	  }
	  ?>
</div>
<?php get_template_part('templates/content', 'page'); ?>
