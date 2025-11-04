WP-Tasks-After-Install
======================

 

Based on Oh Yeah Devs WP Tasks After Install with the following added or
modified changes

V1.91 - Added Disable Search engine visibility - Added Delete Page Privacy
Policy - Changed Default Categories to General

V1.921 - Added Timezone to London/Europe - Added UK Date Format (17th June 2022)

V1.922 - Added Removal of Jetpack as its autoinstalled \@ instaWP and its
annoying.

V2.1 Added removal of sofaculous spam

V2.2 Added in Wordpress 6.7 remove welcome screen and pattern library

V.2.3 Added additional Softaculous plugin removal, added disable avatars and
removed screen options for all but Welcome - the welcome persists as it requires
to be logged into dismiss I will continue looking for a way to do this

V2.3.1 Plugin now self deletes once it is finished

V2.3.2 - Added an additional flush_rewrite_rules(); to the permalink changes so
they are saved and active as was causing some issues with bricks builder and
other page builders

V2.4 Several Improvements & Bug Fixes

-   Fixed Welcome Screen Persistence

-   added  `dashboard_rediscache` to hidden screen options as its auto installed
    by GridPane

-   Removed support for older WPLANG

-   Added support for downloading and installing language packs as needed and
    changing and instantly loading said changes

-   Added a Link to original WordPress Repo Version

-   Change Author Information to show I manage this repo not Oh Yeah Devs

V2.5 Added Ability to write license constants

Added Functions to allow writing licensing constants to your user-config (Grid pane) or your WP-Config everywhere else

Supports env file for forks and also supports direct integration

V2.6  Add new ability to replace plugins on run either by hardcoding them or using a filter

Example below for replacing nginx-helper with one without the load-textdomain issue on gridpane

```
add_filter( 'oaf_wptai_plugin_replacements', function( $items ) {
    $items[] = array(
        'target'   => 'nginx-helper/nginx-helper.php',
        'zip'      => 'https://github.com/stingray82/nginx-helper/releases/latest/download/nginx-helper.zip',
        'activate' => true,
        'force'    => false,
    );
    return $items;
});
```

 

**Note: As it stands with regards to this plugin I am out of ideas and tasks to
be completed currently with the current state of the WordPress dashboard and
options, it now does everything I want it to do, if you know of improvements
please do let me know and I will review and consider them for potential
inclusion.**

 
