=== WP Tasks After Install ===
Contributors: oabadfol, valhallawp, ohyeahdev, fernandoaureonet, reallyusefulplugins, stingray82
Tags: default content, remove, starter, installation, cleanup, initialization
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk
Requires at least: 6.5
Tested up to: 6.7
Requires PHP: 7.2

A powerful automation plugin that performs essential cleanup, configuration, optimisation, and licensing tasks immediately after a fresh WordPress installation.

== Description ==
WP Tasks After Install automatically performs essential cleanup and setup actions immediately after installing WordPress. It is intended to run only once. After completing its tasks, it may automatically deactivate or self-delete (v2.3.1+).

### Core Features
* Removes default content (Hello World, Sample Page)
* Sets permalink structure
* Disables comments and pings
* Deletes unnecessary files (readme.html, wp-config-sample.php)
* Removes all non-default themes
* Removes auto‑installed plugins (Hello Dolly, Akismet, Jetpack, Softaculous plugins, and more)
* Configures timezone, date format, and language
* Hides unwanted dashboard widgets
* Supports plugin replacement system
* Supports automatic Freemius license activation
* Can write license constants to wp-config or GridPane user-config
* Self-deletes when complete

== Installation ==
1. Install plugin
2. Activate plugin once
3. Plugin runs all tasks automatically
4. Plugin deactivates or self-deletes when finished

== Changelog ==
= 2.7 =
* Added Freemius automatic licensing system
* Added helper architecture for future integrations

= 2.6 =
* Added plugin replacement system

= 2.5 =
* Added ability to write license constants
* Added .env support

= 2.4 =
* Fixed welcome screen persistence
* Added removal of dashboard_rediscache widget
* Added language pack installation

= 2.3.2 =
* Added extra flush_rewrite_rules()

= 2.3.1 =
* Plugin now self-deletes when finished

= 2.3 =
* Added more Softaculous removals
* Added disable avatars
* Improved screen options

= 2.2 =
* WordPress 6.7 support
* Removed welcome screen and pattern library

= 2.1 =
* Removed Softaculous spam plugins

= 1.922 =
* Removed Jetpack (InstaWP)

= 1.921 =
* Added timezone and UK date format

= 1.91 =
* Added disable search engine visibility
* Deleted Privacy Policy page
* Changed default category

= 1.0–1.9 =
* Original plugin versions
