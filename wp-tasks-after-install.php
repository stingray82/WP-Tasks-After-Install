<?php
/**
 * Plugin Name: WP Tasks After Install Modified
 * Plugin URI: https://github.com/stingray82/WP-Tasks-After-Install
 * Description: Performs a number of necessary tasks after installing WordPress.
 * Author: Oh Yeah Devs / Stingray82
 * Author URI: https://github.com/stingray82/WP-Tasks-After-Install
 * Version: 2.31
 * License: GPLv2 or later
 * Text Domain: wp-tasks-after-install
 * Domain Path: /languages/
 */

// Go away!!
if ( ! defined( 'WPINC' ) ) {
     die;
}

// Adds plugin internationalization
function oaf_wptai_i18n() {
	load_plugin_textdomain( 'wp-tasks-after-install', FALSE, basename(dirname( __FILE__ ) ) . '/languages/' );
}//end plugin_name_i18n()

add_action( 'plugins_loaded', 'oaf_wptai_i18n' );

add_action( 'admin_init', 'oaf_wptai_remove_default_post');
add_action( 'admin_init', 'oaf_wptai_remove_default_page'); // Removed the privacy page //
add_action( 'admin_init', 'oaf_wptai_time'); // Add Timezone to London and Date to UK Format i.e 17th June 2022
add_action( 'admin_init', 'oaf_wptai_change_uncategorized');
add_action( 'admin_init', 'oaf_wptai_set_permalink_postname' );
add_action( 'admin_init', 'oaf_wptai_delete_plugins' );
add_action( 'admin_init', 'oaf_wptai_disable_comments_and_pings' ); // Search Enginee Added
add_action( 'admin_init', 'oaf_wptai_delete_config_sample_file' );
add_action( 'admin_init', 'oaf_wptai_delete_readme_html_file' );
add_action( 'admin_init', 'oaf_wptai_delete_themes' );
add_action('plugins_loaded', 'oaf_wptai_disable_screen_options_preserve'); // Disable Screen Options
add_action( 'admin_init', 'oaf_wptai_disable_thumbnail_sizes' );
add_action( 'admin_init', 'oaf_wptai_media_settings' );
add_action( 'admin_init', 'oaf_wptai_disable_patten_guide' ); // Added in Wordpress 6.7 Toggle That Switch
add_action('init', 'oaf_wptai_disable_avatars_in_discussion_settings'); // Disable Avatars
add_action( 'admin_init', 'oaf_wptai_deactivate_this_plugin' );

// Remove default post 'Hello Word'
function oaf_wptai_remove_default_post() {

	if ( FALSE === get_post_status( 1 ) ) {
	   	// The post does not exist - do nothing.
	} else {
	   	wp_delete_post(1);
	}

} // end of oaf_wptai_remove_default_post() function.

// Remove the default example page
function oaf_wptai_remove_default_page() {

	if ( FALSE === get_post_status( 2 ) ) {
	   	// The page does not exist - do nothing.
	} else {
	   	wp_delete_post(2);
	}
	if ( FALSE === get_post_status( 3 ) ) {
	   	// The page does not exist - do nothing.
	} else {
	   	wp_delete_post(3);
	}

} // end of oaf_wptai_remove_default_page() function


// Change the name and slug of default category to news
function oaf_wptai_change_uncategorized() {

	$term = term_exists( __('Uncategorized', 'wp-tasks-after-install', 'wp-tai'), 'category'); // check if 'uncategorized' category exists

	if ($term !== 0 && $term !== null) {  // if exists change name and slug
	  wp_update_term(1, 'category', array(
	  	'name' => __( 'General', 'wp-tasks-after-install', 'wp-tai' ),
	  	'slug' => __( 'general', 'wp-tasks-after-install', 'wp-tai' )
	  ));
	}

} // end of oaf_wptai_change_uncategorized() function.


// Set permlinks to postname  /%postname%/
function oaf_wptai_set_permalink_postname() {

    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );

} // end of oaf_wptai_set_permalink_postname() function.


// remove hello world and akismet plugins // 08-11 Add to remove all the softacculous based plugins!
function oaf_wptai_delete_plugins() {
    // List of plugins to check, deactivate, and delete
    $plugins = array(
    	// Base Install / InstaWP
        'hello.php',
        'akismet/akismet.php',
        // Softaccilous Spam
        'backuply/backuply.php',
        'backuply-pro/backuply-pro.php',
        'gosmtp/gosmtp.php',
        'gosmtp-pro/gosmtp-pro.php',
        'loginizer/loginizer.php',
        'loginizer-security/loginizer-security.php',
        'pagelayer/pagelayer.php',
        'pagelayer-pro/pagelayer-pro.php',
        'siteseo/siteseo.php',
        'siteseo-pro/siteseo-pro.php',
        'softaculous-pro/softaculous-pro.php',
        'speedycache/speedycache.php',
        'speedycache-pro/speedycache-pro.php',
        'fileorganizer/fileorganizer.php',
        'fileorganizer-pro/fileorganizer-pro.php',
    );

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Include the plugin functions file

    foreach ( $plugins as $plugin ) {
        if ( is_plugin_active( $plugin ) ) {
            deactivate_plugins( $plugin ); // Deactivate the plugin if it's active
        }
    }

    // After deactivation, delete the plugins
    delete_plugins( $plugins );
} // End of oaf_wptai_delete_plugins function



// Set Timezone, Date, and Site Language - Modified 2.0
function oaf_wptai_time() {
    update_option( 'timezone_string', 'Europe/London' );
    update_option( 'date_format', 'j F Y' );
    
      // Set site language to British English (en_GB)
    update_option( 'WPLANG', 'en_GB' ); // For older versions of WordPress (if applicable)
    update_option( 'locale', 'en_GB' ); // For more modern versions of WordPress
    
    // Also ensure it's set in the general settings
    update_option( 'site_language', 'en_GB' );
} // end of oaf_wptai_time function.

// Disable comments, Search Enginees and trackbacks
function oaf_wptai_disable_comments_and_pings() {

	// Disable pings
	if( '' != get_option( 'default_ping_status' ) ) {
		update_option( 'default_ping_status', '' );
	} // end if

	// Disable comments
	if( '' != get_option( 'default_comment_status' ) ) {
		update_option( 'default_comment_status', '' );
	} // end if

	// Discourage search engines from indexing this site
	if( '' != get_option( 'blog_public' ) ) {
		update_option( 'blog_public', '' );
	} // end if

} // end oaf_wptai_disable_comments_and_pings() function.


// Delete wp-config-sample.php file
function oaf_wptai_delete_config_sample_file() {

	$url_config_sample = "wp-config-sample.php";
	$abspath=$_SERVER['DOCUMENT_ROOT'];
	$file_url = $abspath . '/' . $url_config_sample;
	if (file_exists($file_url)) {
	    unlink($file_url);
	}

} // end of oaf_wptai_delete_config_sample_file() function.

// Delete readme.html file
function oaf_wptai_delete_readme_html_file() {

	$url_readme_html = "readme.html";
	$abspath=$_SERVER['DOCUMENT_ROOT'];
	$file_url = $abspath . '/' . $url_readme_html;
	if (file_exists($file_url)) {
	    unlink($file_url);
	}

} // end of oaf_wptai_delete_readme_html_file() function.


// Remove unactivated themes
function oaf_wptai_delete_themes() {

	// The current themes.
	$installed_themes = wp_get_themes();

	// The themes we want to keep (delete the others).
	$theme_data = wp_get_theme();
	$current_theme = $theme_data->get( 'TextDomain' );

	$themes_to_keep = array( $current_theme );

	// Loop through installed themes.
	foreach ( $installed_themes as $theme ) {

		// The name of the theme.
		$name = $theme->get_template();

		// If it's not one we want to keep...
		if ( ! in_array( $name, $themes_to_keep ) ) {
			$stylesheet = $theme->get_stylesheet();

			// Delete the theme.
			delete_theme( $stylesheet, false );
		}
	} // end of foreach - themes

} // end of oaf_wptai_delete_themes() function.


// Disable all default thumbnail sizes and uncheck thumbnail cropping to exact dimensions added 2.0
function oaf_wptai_disable_thumbnail_sizes() {
    // Remove default image sizes
    remove_image_size( 'thumbnail' );
    remove_image_size( 'medium' );
    remove_image_size( 'medium_large' );
    remove_image_size( 'large' );
    remove_image_size( '1536x1536' ); // Default WP size for high resolution
    remove_image_size( '2048x2048' ); // Default WP size for high resolution

    // Set default image sizes to 0 to prevent generation of these sizes
    update_option( 'thumbnail_size_w', 0 );
    update_option( 'thumbnail_size_h', 0 );
    update_option( 'medium_size_w', 0 );
    update_option( 'medium_size_h', 0 );
    update_option( 'medium_large_size_w', 0 );
    update_option( 'medium_large_size_h', 0 );
    update_option( 'large_size_w', 0 );
    update_option( 'large_size_h', 0 );

    // Uncheck the crop to exact dimensions option
    update_option( 'thumbnail_crop', 0 ); // 0 disables cropping, 1 enables cropping
}





// Deactivate this plugin.
function oaf_wptai_deactivate_this_plugin() {

	if ( !function_exists( 'deactivate_plugins' ) ) {
	    require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	deactivate_plugins( plugin_basename( __FILE__ ) );
    
      // Delete this plugin
    $plugin_file = plugin_basename(__FILE__); // Get the plugin's relative path
    delete_plugins([$plugin_file]);

} // end of oaf_wptai_deactivate_this_plugin() function.

// Set media upload settings added 2.0
function oaf_wptai_media_settings() {
    // Disable cropping of large images
    update_option( 'big_image_size_threshold', 0 );

    // Disable automatic scaling of uploaded images
    update_option( 'uploads_use_yearmonth_folders', 0 );
}

//Added in Wordpress 6.7 remove welcome screen and pattern library
function oaf_wptai_disable_patten_guide() {
	global $wpdb;

    // Retrieve all users
    $users = get_users();

    foreach ( $users as $user ) {
        // Check and update persisted preferences
        $meta_key = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT meta_key FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key LIKE %s",
                $user->ID,
                '%_persisted_preferences'
            )
        );

        if ( ! $meta_key ) {
            $meta_key = $wpdb->prefix . 'persisted_preferences';
        }

        $persisted_preference = get_user_meta( $user->ID, $meta_key, true );

        if ( ! is_array( $persisted_preference ) ) {
            $persisted_preference = array();
        }

        if ( ! isset( $persisted_preference['core'] ) ) {
            $persisted_preference['core'] = array();
        }
        if ( ! isset( $persisted_preference['core/edit-post'] ) ) {
            $persisted_preference['core/edit-post'] = array();
        }

        $persisted_preference['core']['isComplementaryAreaVisible'] = false;
        $persisted_preference['core']['enableChoosePatternModal'] = false;
        $persisted_preference['core/edit-post']['welcomeGuide'] = false;

        $persisted_preference['_modified'] = gmdate( 'Y-m-d\TH:i:s.v\Z' );

        update_user_meta( $user->ID, $meta_key, $persisted_preference );

        // Add or update `show_welcome_panel`
        update_user_meta( $user->ID, 'show_welcome_panel', 1 );
    }
}

// 2.3 Hide widgets and Disable Avatars 
// Disable individual screen options for all users while preserving existing hidden widgets + Welcome Panel
function oaf_wptai_disable_screen_options_preserve() {
    $users = get_users();

    // Widgets to hide
    $hidden_widgets = array(
        'dashboard_activity',
        'dashboard_quick_press',
        'dashboard_site_health',
        'dashboard_right_now',
        'dashboard_primary',
    );

    foreach ( $users as $user ) {
        $user_id = $user->ID;

        // Update hidden dashboard widgets
        $current_hidden = get_user_meta( $user_id, 'metaboxhidden_dashboard', true ) ?: [];
        $updated_hidden = array_unique( array_merge( $current_hidden, $hidden_widgets ) );
        update_user_meta( $user_id, 'metaboxhidden_dashboard', $updated_hidden );
        
        // Issues with persistant showing so lets delete and then re-add
        // Delete the meta key first to ensure it gets updated
        delete_user_meta( $user_id, 'show_welcome_panel' );

        // Add the meta key with the desired value
        update_user_meta( $user_id, 'show_welcome_panel', '0' ); // this doesn't work there must be something that persists that requires login which means this plugin may not be able to do it

    }
}


function oaf_wptai_disable_avatars_in_discussion_settings() {
    // Update the 'show_avatars' option to '0' (disabled)
    update_option('show_avatars', 0);
}