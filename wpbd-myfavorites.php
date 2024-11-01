<?php
/**
 * Plugin Name:  Wpbd My Favorites
 * Plugin URI:   https://ludosmundi.com
 * Description:  Shows favorites post on member profile. Requires Buddypress
 * Version:      1.1.1
 * Author:       fredd
 * Text Domain: wpbd-my-favorites
 * Domain Path: /languages
 * Author URI:   https://ludosmundi.com/
 *
 */


define('MYFAVORITES_VERSION', '1.1.1');

/**
 * Register admin menu
 */
function myfavorites_admin_menu() {
  
    add_options_page(
      __('Wpbd-MyFavorites',  'wpbd-my-favorites') ,
      __('Wpbd-MyFavorites',  'wpbd-my-favorites'),
      'manage_options' ,
      basename(__FILE__),
      'myfavorites_options_page');
}
add_action('admin_menu', 'myfavorites_admin_menu');


/**
 * Init Wpbd-MyFavorites
 */
function myfavorites_init() {

  load_plugin_textdomain( 'wpbd-my-favorites', false, dirname( plugin_basename( __FILE__ ) ) .'/languages' );

  if ( ! is_admin() ) {
    if ( get_option('myfavorites_include_css') ) {
      wp_register_style('Wpbd-MyFavorites', WP_PLUGIN_URL . '/wpbd-my-favorites/wpbd-my-favorites.css');
      wp_enqueue_style( 'Wpbd-MyFavorites');
    }
  }
}
add_action ( 'init', 'myfavorites_init' );


function myfavorites_options_page()

{
    if(isset($_POST['vfavo']) and !empty($_POST['vfavo']))
    {
        if(ctype_digit($_POST['vfavo']))
        {
            update_option('view_myfavorites', $_POST['vfavo']);
            update_option('view_myfavoriteslinkt', $_POST['vfavolt']);
            update_option('view_myfavoritestype', $_POST['vfavtype']);
            echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved', 'wpbd-my-favorites').'</strong></p></div>';
        }

        else
        {
            echo '<div id="message" class="updated fade"><p><strong>'.__('You entered an invalid value', 'wpbd-my-favorites').'</strong></p></div>';
        }
    }
    echo myfavorites_output_options_page();
}


function myfavorites_output_options_page()

{

    return '<div class=wrap>
	<form method="post">
    <h2>'.__('How many display favorites', 'wpbd-my-favorites').'</h2>
    <fieldset class="options" name="general">
      <legend>'.__('General settings', 'wpbd-my-favorites').'</legend>
<table width="100%" cellspacing="2" cellpadding="5" class="editform">
<tr><td>'.__('Configure how many favorites you want to display on profile page and more.', 'wpbd-my-favorites').'</td></tr>
</table>
      <table width="100%" cellspacing="2" cellpadding="5" class="editform">
<tr>
          <th nowrap valign="top" width="33%">'.__('Favorites number', 'wpbd-my-favorites').'</th>
           <td><input type="text" name="vfavo" id="vfavo" value="' . get_option('view_myfavorites') . '" /> <span class="description">'.__('Determines how much favorites you want to display.', 'wpbd-my-favorites').'</span>
           </td>
        </tr>
<tr>
          <th nowrap valign="top" width="33%">'.__('Favorites link name', 'wpbd-my-favorites').'</th>
           <td><input type="text" name="vfavolt" id="vfavolt" value="' . get_option('view_myfavoriteslinkt') . '" /> <span class="description">'.__('Determines link name on profil.', 'wpbd-my-favorites').'</span>
           </td>
        </tr>
<tr>
<th nowrap valign="top" width="33%">'.__('How to display the favorites', 'wpbd-my-favorites').'</th>
<td>
<select name="vfavtype" size="1">
						<option value="0" '.selected(get_option('view_myfavoritestype'), 0, false).'>'.__('Thumbnail', 'wpbd-my-favorites').'</option>
						<option value="1" '.selected(get_option('view_myfavoritestype'), 1, false).'>'.__('Link', 'wpbd-my-favorites').'</option>
					</select>
</td>
</tr>
      </table>
    </fieldset>



    <p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="'.__('Save Changes', 'wpbd-my-favorites').'" />
	</p>
  </form>';
}


function myfavorites_buddypress() {
  if ( function_exists( 'buddypress' ) && buddypress() && ! buddypress()->maintenance_mode && bp_is_active( 'xprofile' ) ) {
    require_once( 'includes/myfavorites-buddypress.php' );
    return new MyFavorites_BuddyPress_Component();
  }
}
add_action( 'bp_setup_components', 'myfavorites_buddypress' );

/**
 * Wpbd-MyFavorites Install Function
 */
function myfavorites_install() {

  if ( !get_option('view_myfavorites') ) {
    add_option('view_myfavorites', '20');
    add_option('view_myfavoriteslinkt', 'Favorites');
    add_option('view_myfavoritestype', '0');
  }

  add_option('myfavorites_include_css', true);
}
register_activation_hook  ( __FILE__, 'myfavorites_install' );

//Desinstallation
register_deactivation_hook(__FILE__, 'myfavorites_uninstall');
function myfavorites_uninstall()

{
delete_option('view_myfavorites');
delete_option('view_myfavoriteslinkt');
delete_option('view_myfavoritestype');
}
