<?php
/**
 * BuddyPress Content Template
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Get user ID
$user_id = bp_displayed_user_id();
?>
<div class="profile-box items-following">

    <h3><?php _e('Favorites', 'wpbd-my-favorites'); ?></h3>

    <div class="profile-content">

        <?php
global $wpfp_options;
$theactuauser = wpfp_get_users_favorites($GLOBALS['bp']->displayed_user->userdata->user_login);
$favorite_post_ids = $theactuauser;
        
if($favorite_post_ids)  {
		$favorite_post_ids = array_reverse($favorite_post_ids);
        $post_per_page = get_option('view_myfavorites');

        $page = intval(get_query_var('paged'));

        $qry = array('post__in' => $favorite_post_ids, 'posts_per_page'=> $post_per_page, 'orderby' => 'post__in', 'paged' => $page);
        
        query_posts($qry);

        while ( have_posts() ) : the_post();
//thumbnail my arcadeplugin
$gamethumb1 = get_post_meta(get_the_ID(), "mabp_thumbnail_url", true);
//thumbnail post wordpress
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
$gamethumb2 = $thumb['0'];

		if ($gamethumb1 || $gamethumb2 =='')
		{
            $urlofsite = get_bloginfo('url');
            $gamethumb = $urlofsite.'/wp-content/plugins/wpbp-my-favorites/template/other.png';
		}
       if ($gamethumb1 !='')
		{
            $gamethumb = $gamethumb1;
		}
       if ($gamethumb2 !='')
		{
            $gamethumb = $gamethumb2;
		}
if (get_option('view_myfavoritestype') == 0)
{
            echo'<a href="'.get_permalink().'" title="'.get_the_title().'"><img class="thumbfav" src="'.$gamethumb.'" /></a>';
}
else
{
            echo'<a href="'.get_permalink().'" title="'.get_the_title().'" class="linkfav">'.get_the_title().'</a>';
}
        endwhile;
echo '<a href="http://www.gamix.fr" target="_blank" title="Gamix" class="linkcopy" rel="follow">By Gamix</a>';
}
?>
</div>
</div>