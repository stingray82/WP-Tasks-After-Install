<?php
/**
 * Plugin Name:       WP Tasks After Install Modified
 * Tested up to:      6.8.3
 * Description:       Performs a number of necessary tasks after installing WordPress.
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Version:           2.7
 * Author:            Stingray82 / Oh Yeah Devs
 * Author URI:        https://github.com/stingray82/WP-Tasks-After-Install
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-tasks-after-install
 * Website:           https://reallyusefulplugins.com
 * */




/* This plugin is based on the original plugin here  * Based on https://wordpress.org/plugins/wp-tasks-after-install it has been very heavily modified and updated */

// Go away!!
if ( ! defined( 'WPINC' ) ) {
     die;
}

// Adds plugin internationalization
function oaf_wptai_i18n() {
	load_plugin_textdomain( 'wp-tasks-after-install', FALSE, basename(dirname( __FILE__ ) ) . '/languages/' );
}//end plugin_name_i18n()

define( 'OAF_WPTAI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', 'oaf_wptai_i18n' );
add_action( 'plugins_loaded', 'oaf_wptai_load_includes' );
add_action( 'plugins_loaded', 'oaf_wptai_freemius_autoactivate' );

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
add_action( 'admin_init', 'oaf_wptai_write_config_constants' ); // Writes to WP Config or User Config on Gridpane
add_action( 'admin_init', 'oaf_wptai_replace_plugins_batch', 5 ); // Batch Replace Plugins
add_action( 'admin_init', 'oaf_wptai_deactivate_this_plugin', 999 ); // deactivate


// Add our Helper Includes
function oaf_wptai_load_includes() {
    $includes = [
        'freemius.php',
    ];

    foreach ( $includes as $file ) {
        $file_path = OAF_WPTAI_PLUGIN_DIR . 'helper/' . $file;
        
        if ( file_exists( $file_path ) ) {
            require_once $file_path;
        } else {
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( "OAF_WPTAI: Failed to load $file_path" );
            }
        }
    }
}





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


// Change the name and slug of default category to General
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
    flush_rewrite_rules(); // Added in 2.32 to try and save having to keep saving permalinks

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
/* Version 2.33 Removed Legacy WPLANG and Update Options override's Will now actually download and install lanaguage pack and then set it up for you as instructed and then if successful it will switch update the option and then switch the locale as needed */
function oaf_wptai_time() {
    // Set timezone and date format
    update_option( 'timezone_string', 'Europe/London' );
    update_option( 'date_format', 'j F Y' );

    // Desired language locale
    $new_locale = 'en_GB';

    // Ensure the translation functions are available
    if ( ! function_exists( 'wp_download_language_pack' ) ) {
        require_once ABSPATH . 'wp-admin/includes/translation-install.php';
    }

    // Check if the language pack is already installed
    if ( ! in_array( $new_locale, get_available_languages(), true ) ) {
        // Download the language pack if not installed
        $downloaded = wp_download_language_pack( $new_locale );

        if ( ! $downloaded ) {
            // Log or handle the error if download fails
            error_log( "Failed to download the language pack for locale: $new_locale." );

            return;
        }
    }

    // Update the 'WPLANG' option in the database
    update_option( 'WPLANG', $new_locale );

    // Ensure the new locale is loaded immediately
    switch_to_locale( $new_locale );
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
        update_user_meta( $user->ID, 'show_welcome_panel', 0 ); // Updated V2.33
    }
}

// 2.3 Hide widgets and Disable Avatars 
// Disable individual screen options for all users while preserving existing hidden widgets + Welcome Panel
// 2.33 Added 'dashboard_rediscache' for GridPane for that Clean Look
function oaf_wptai_disable_screen_options_preserve() {
    $users = get_users();

    // Widgets to hide
    $hidden_widgets = array(
        'dashboard_activity',
        'dashboard_quick_press',
        'dashboard_site_health',
        'dashboard_right_now',
        'dashboard_primary',
        'dashboard_rediscache',
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
        update_user_meta( $user_id, 'show_welcome_panel', '0' ); // This now Works as intended 2.33

    }
}


function oaf_wptai_disable_avatars_in_discussion_settings() {
    // Update the 'show_avatars' option to '0' (disabled)
    update_option('show_avatars', 0);
}


// Write/replace license constants to GridPane user-configs.php if present, else wp-config.php
add_action( 'admin_init', 'oaf_wptai_write_config_constants' );

function oaf_wptai_write_config_constants() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) return;

    // ---- desired constants (env/file/committed) ----
    $constants_env = array(
        // 'ACF_PRO_LICENSE'      => getenv('ACF_PRO_LICENSE'),
        // 'GRAVITYFORMS_LICENSE' => getenv('GRAVITYFORMS_LICENSE'),
        // 'WP_ROCKET_KEY'        => getenv('WP_ROCKET_KEY'),
        // 'WPMAIL_SMTP_LICENSE'  => getenv('WPMAIL_SMTP_LICENSE'),
    );
    $constants_direct = array(
        
        //'API_KEY' => 'Your-API-HERE',

    );
    $desired = array_merge( $constants_direct, array_filter( $constants_env, fn($v)=>is_string($v)&&$v!=='' ) );
    if ( empty( $desired ) ) return;

    $paths = oaf_wptai_locate_config_paths();
    $target = $paths['target'];
    if ( ! $target || ! file_exists($target) || ! is_writable($target) ) return;

    $contents = file_get_contents($target);
    if ( $contents === false ) return;

    $begin_marker = "// BEGIN WP Tasks After Install – License Constants";
    $end_marker   = "// END WP Tasks After Install – License Constants";

    // 1) Find existing managed block (if any)
    $block_regex = '/' . preg_quote($begin_marker,'/') . '.*?' . preg_quote($end_marker,'/') . '\s*/s';
    $has_block   = (bool) preg_match($block_regex, $contents, $m, PREG_OFFSET_CAPTURE);

    // 2) Build list of constants defined OUTSIDE our block (cannot override those)
    $contents_without_block = $has_block
        ? substr($contents, 0, $m[0][1]) . substr($contents, $m[0][1] + strlen($m[0][0]))
        : $contents;

    $defined_outside = array();
    foreach ( array_keys($desired) as $name ) {
        $pat = '/\bdefine\s*\(\s*[\'"]' . preg_quote($name,'/') . '[\'"]\s*,/i';
        if ( preg_match($pat, $contents_without_block) ) {
            $defined_outside[$name] = true;
        }
    }

    // 3) Prepare new block lines
    $lines = array();
    foreach ( $desired as $name => $val ) {
        if ($val === null || $val === '') continue;

        if ( isset($defined_outside[$name]) ) {
            // Cannot redefine; optionally document it
            $lines[] = "// NOTE: {$name} already defined elsewhere; leaving as-is.";
            continue;
        }

        $escaped = str_replace(array('\\',"'"), array('\\\\',"\\'"), (string)$val);
        $lines[] = "define( '{$name}', '{$escaped}' );";
    }

    // If nothing to write and block didn't exist, bail
    if ( empty($lines) && ! $has_block ) return;

    $new_block = $begin_marker . PHP_EOL
               . implode(PHP_EOL, $lines) . PHP_EOL
               . $end_marker . PHP_EOL;

    // 4) Insert/replace block
    if ( $paths['is_gridpane'] ) {
        // GridPane user-configs.php: replace or append block
        if ( $has_block ) {
            $contents = preg_replace($block_regex, $new_block, $contents, 1);
        } else {
            if ( preg_match('/\?>\s*$/', $contents) ) {
                $contents = preg_replace('/\?>\s*$/', PHP_EOL . $new_block . '?>' . PHP_EOL, $contents, 1);
            } else {
                $contents = rtrim($contents) . PHP_EOL . PHP_EOL . $new_block;
                if ( strpos(ltrim($contents), '<?php') !== 0 ) {
                    $contents = "<?php\n" . $contents;
                }
            }
        }
    } else {
        // wp-config.php: prefer before "That's all, stop editing!"
        $stop_regex = '/^[ \t]*\/\*+\s*That\'s all, stop editing!.*?\*+\/\s*$/mi';
        if ( $has_block ) {
            $contents = preg_replace($block_regex, $new_block, $contents, 1);
        } elseif ( preg_match($stop_regex, $contents, $mm, PREG_OFFSET_CAPTURE) ) {
            $pos = $mm[0][1];
            $contents = rtrim(substr($contents,0,$pos)) . PHP_EOL . $new_block . PHP_EOL . ltrim(substr($contents,$pos));
        } else {
            $contents = rtrim($contents) . PHP_EOL . PHP_EOL . $new_block;
        }
    }

    // 5) Backup + atomic write
    @copy( $target, $target . '.' . gmdate('Ymd-His') . '.bak' );
    $tmp = $target . '.tmp-' . wp_generate_password(8,false,false);
    $ok  = ( false !== file_put_contents($tmp, $contents) ) && @rename($tmp, $target);
    if ( ! $ok ) @unlink($tmp);
}

/**
 * Prefer GridPane user-configs.php; fallback to wp-config.php.
 * @return array{target:string|false,is_gridpane:bool,existing:string[]}
 */
function oaf_wptai_locate_config_paths() {
    $roots = array( ABSPATH, dirname(ABSPATH) . '/' );
    $user_configs = array_map(fn($r)=> rtrim($r,'/').'/user-configs.php', $roots);
    $wp_configs   = array_map(fn($r)=> rtrim($r,'/').'/wp-config.php',   $roots);

    foreach ( $user_configs as $uc ) {
        if ( file_exists($uc) && is_writable($uc) ) {
            return array('target'=>$uc,'is_gridpane'=>true,'existing'=>array_merge($user_configs,$wp_configs));
        }
    }
    foreach ( $wp_configs as $wc ) {
        if ( file_exists($wc) && is_writable($wc) ) {
            return array('target'=>$wc,'is_gridpane'=>false,'existing'=>array_merge($user_configs,$wp_configs));
        }
    }
    return array('target'=>false,'is_gridpane'=>false,'existing'=>array_merge($user_configs,$wp_configs));
}

/**
 * Install/replace a single plugin from a ZIP URL, but only if it already exists (by default).
 *
 * @param string $target_slug         Plugin main file path, e.g. 'nginx-helper/nginx-helper.php'.
 * @param string $zip_url             Direct URL to a plugin .zip.
 * @param bool   $activate            Activate after install (default: true).
 * @param bool   $force               Ignore per-site "already done" guard (default: false). (unused if you keep the guard commented)
 * @param bool   $install_if_missing  If true, will install even if plugin wasn't present. Default false.
 * @return array                      ['ok'=>bool, 'status'=>string, 'error'=>string|null]
 */
function oaf_wptai_install_or_replace_plugin( $target_slug, $zip_url, $activate = true, $force = false, $install_if_missing = false ) {
    if ( ! is_admin() || ! current_user_can( 'install_plugins' ) ) {
        return array('ok'=>false,'status'=>'not_allowed','error'=>null);
    }

    // Core APIs
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

    // Is the target plugin currently installed?
    $plugin_file   = WP_PLUGIN_DIR . '/' . $target_slug;
    $plugin_folder = WP_PLUGIN_DIR . '/' . dirname( $target_slug );
    $installed_map = function_exists('get_plugins') ? get_plugins() : array();
    $is_installed  = file_exists( $plugin_folder ) || isset( $installed_map[ $target_slug ] );

    if ( ! $is_installed && ! $install_if_missing ) {
        // Skip completely if it wasn't there to begin with
        return array('ok'=>true,'status'=>'skipped_not_installed','error'=>null);
    }

    // (Optional) once-only guard — keep commented if you don't want it
    /*
    $done = get_option( 'oaf_wptai_replaced_plugins', array() );
    if ( ! is_array( $done ) ) $done = array();
    if ( ! $force && ! empty( $done[ $target_slug ] ) ) {
        return array('ok'=>true,'status'=>'skipped_already_done','error'=>null);
    }
    */

    // Deactivate if active
    if ( is_plugin_active( $target_slug ) ) {
        deactivate_plugins( $target_slug, true );
    }

    // Delete existing plugin (only if it actually exists)
    if ( $is_installed ) {
        $del_result = delete_plugins( array( $target_slug ) );
        if ( is_wp_error( $del_result ) ) {
            return array('ok'=>false,'status'=>'delete_failed','error'=>$del_result->get_error_message());
        }
    }

    // Ensure FS is ready
    if ( ! WP_Filesystem() ) {
        return array('ok'=>false,'status'=>'fs_not_ready','error'=>'Filesystem credentials required');
    }

    // Install from ZIP
    $skin     = new Automatic_Upgrader_Skin();
    $upgrader = new Plugin_Upgrader( $skin );
    $result   = $upgrader->install( $zip_url );

    if ( is_wp_error( $result ) ) {
        return array('ok'=>false,'status'=>'install_failed','error'=>$result->get_error_message());
    }
    if ( ! $result ) {
        return array('ok'=>false,'status'=>'install_failed','error'=>'Unknown install failure');
    }

    // Which file to activate?
    $activate_file = $target_slug;
    if ( ! file_exists( WP_PLUGIN_DIR . '/' . $activate_file ) ) {
        $pi = method_exists( $upgrader, 'plugin_info' ) ? $upgrader->plugin_info() : '';
        if ( ! empty( $pi ) ) {
            $activate_file = $pi;
        }
    }

    if ( $activate && $activate_file ) {
        $act = activate_plugin( $activate_file );
        if ( is_wp_error( $act ) ) {
            return array('ok'=>false,'status'=>'activated_failed','error'=>$act->get_error_message());
        }
    }

    // (Optional) mark done if you re-enable the guard
    /*
    $done[ $target_slug ] = array(
        'ts'     => time(),
        'status' => $activate ? 'installed_and_activated' : 'installed',
        'zip'    => $zip_url,
    );
    update_option( 'oaf_wptai_replaced_plugins', $done, false );
    */

    return array('ok'=>true,'status'=>'done','error'=>null);
}


/**
 * Batch replace/install plugins from filtered specs.
 * Backward compatible:
 * - items without 'install_if_missing' will default to false (skip if not installed)
 * - accepts alias keys: 'install', 'always_install' (truthy => install_if_missing = true)
 * - supports both associative and simple positional arrays: ['target','zip',true,false]
 */
function oaf_wptai_replace_plugins_batch() {
    if ( ! is_admin() || ! current_user_can( 'install_plugins' ) ) return;

    // Start empty; everything comes from the filter
    $replacements = apply_filters( 'oaf_wptai_plugin_replacements', array() );

    if ( empty( $replacements ) || ! is_array( $replacements ) ) return;

    foreach ( $replacements as $item ) {
        $target             = isset( $item['target'] )             ? trim( (string) $item['target'] )             : '';
        $zip                = isset( $item['zip'] )                ? trim( (string) $item['zip'] )                : '';
        $activate           = isset( $item['activate'] )           ? (bool) $item['activate']                     : true;
        $force              = isset( $item['force'] )              ? (bool) $item['force']                        : false;
        $install_if_missing = isset( $item['install_if_missing'] ) ? (bool) $item['install_if_missing']           : false;

        if ( $target && $zip ) {
            // 5th param controls “only replace if already present”
            oaf_wptai_install_or_replace_plugin( $target, $zip, $activate, $force, $install_if_missing );
        }
    }
}



/* Example Filter 
add_filter( 'oaf_wptai_plugin_replacements', function( $items ) {
    $items[] = array(
        'target'   => 'nginx-helper/nginx-helper.php',
        'zip'      => 'https://github.com/stingray82/nginx-helper/releases/latest/download/nginx-helper.zip',
        'activate' => true,
        'force'    => false,
        // 'install_if_missing' omitted on purpose (defaults to false)

        
       
    );
    return $items;
});
*/

/**
 * Auto-activate Freemius licenses for multiple plugins.
 */
function oaf_wptai_freemius_autoactivate() {
    /**
     * Filter: allow other code to register Freemius plugins for auto-activation.
     *
     * Expected format:
     * array(
     *     'freemius_shortcode' => 'LICENSE_KEY',
     *     'my_prefix_fs'       => 'XXXX-XXXX-XXXX-XXXX',
     *     'my_other_prefix_fs' => 'YYYY-YYYY-YYYY-YYYY',
     * );
     *
     * The shortcode is usually the Freemius helper function name, e.g. my_prefix_fs().
     */
    $freemius_plugins = apply_filters( 'oaf_wptai_freemius_plugins', array() );

    if ( empty( $freemius_plugins ) || ! is_array( $freemius_plugins ) ) {
        return;
    }

    foreach ( $freemius_plugins as $fs_shortcode => $license_key ) {
        if ( empty( $fs_shortcode ) || empty( $license_key ) ) {
            continue;
        }

        // Only construct if the Freemius instance is actually present.
        if ( function_exists( $fs_shortcode ) || isset( $GLOBALS[ $fs_shortcode ] ) ) {
            new Freemius_License_Auto_Activator( $fs_shortcode, $license_key );
        }
    }
}

/*
add_filter( 'oaf_wptai_freemius_plugins', function( $plugins ) {
    $plugins['XXXX']       = 'sk_XXXXXX';
    return $plugins;
} ); 
*/