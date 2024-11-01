<?php
/**
 * My Favorites BuddyPress integration.
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'BP_Component' ) ) :
  class MyFavorites_BuddyPress_Component extends BP_Component {

    public function __construct() {
      parent::start(
        'favorites',
        __('Favorites', 'wpbd-my-favorites'),
        BP_PLUGIN_DIR
      );
    }

    public function setup_globals( $args = '' ) {
      parent::setup_globals( array(
          'has_directory' => true,
          'root_slug'     => 'favorites',
          'slug'          => 'favorites'
        )
      );
    }

    public function setup_actions() {
      add_action( 'bp_init', array( $this, 'init_components'), 7 );
      parent::setup_actions();
    }

    public function init_components() {
      if ( ! bp_is_active( 'activity') )
        return;
    }

    public function setup_nav( $main_nav = '', $sub_nav = '' ) {

      // Stop if there is no user displayed or logged in
      if ( !is_user_logged_in() && !bp_displayed_user_id() )
        return;

      $main_nav = array(
        'default_subnav_slug' => 'all',
        'item_css_id'         => $this->id,
        'name'                => get_option('view_myfavoriteslinkt'),
        'position'            => 100,
        'screen_function'     => 'myfavorites_bp_members_my_favorites',
        'slug'                => $this->slug
      );

      // Determinate user to use for the link
      if ( bp_displayed_user_domain() )
        $user_domain = bp_displayed_user_domain ();
      elseif ( bp_loggedin_user_domain() )
        $user_domain = bp_loggedin_user_domain();
      else
        return;

      $sub_nav = array(
        'item_css_id'   => "{$this->id}-all",
        'name'          => __("My Favorites", 'wpbd-my-favorites'),
        'parent_slug'   => $this->slug,
        'parent_url'    => trailingslashit( $user_domain . $this->slug ),
        'position'      => 20,
        'screen_function' => 'myfavorites_bp_members_my_favorites',
        'slug'          => 'all'
      );

      parent::setup_nav( $main_nav, $sub_nav );
    }


    public function setup_title() {
      global $bp;

      if (  bp_is_activity_component() ) {
        if ( bp_is_my_profile() ) {
          $bp->bp_options_title = __( 'My Favorites', 'wpbd-my-favorites');
        }
      }

      parent::setup_title();
    }
  }

  function myfavorites_bp_members_my_favorites() {
    add_action( 'bp_template_content', 'myfavorites_bp_members_my_favorites_content' );
    bp_core_load_template( apply_filters( 'myfavorites_bp_members_my_favorites', 'members/single/plugins' ) );
  }

  function myfavorites_bp_members_my_favorites_content() {
    $template = WP_PLUGIN_DIR . '/wpbd-my-favorites/template/bp-my-favorites.php';
    if ( file_exists( $template ) )
      include( $template );
  }
endif;