<?php
/**
 * Plugin Name: WP Tasks After Install Modified
 * Plugin URI: https://github.com/stingray82/WP-Tasks-After-Install
 * Description: Performs a number of necessary tasks after installing WordPress.
 * Author: Oh Yeah Devs / Stingray82
 * Author URI: https://github.com/stingray82/WP-Tasks-After-Install
 * Version: 1.922
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


// remove hello world and akismet plugins
function oaf_wptai_delete_plugins() {

    $plugins = array( 'hello.php', 'jetpack/jetpack.php', 'akismet/akismet.php' );
	delete_plugins( $plugins );

} // end of oaf_wptai_delete_plugins function.


// Set Timezone & Date
function oaf_wptai_time() {
	update_option( 'timezone_string', 'Europe/London' );
	update_option( 'date_format', 'j F Y' );
	update_option( 'Site Language', 'en_GB' );


} // end of oaf_wptai_time.


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

// Deactivate this plugin.
function oaf_wptai_deactivate_this_plugin() {

	if ( !function_exists( 'deactivate_plugins' ) ) {
	    require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	deactivate_plugins( plugin_basename( __FILE__ ) );

} // end of oaf_wptai_deactivate_this_plugin() function.
