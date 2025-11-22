# Changelog

## 2.7 – Freemius Auto-Activation + Extensible Helper Framework
- Added new Freemius license activation helper.
- Added `oaf_wptai_freemius_plugins` filter for automatic Freemius plugin activation.
- Introduced helper framework to support new automated systems in future.

**Example usage:**
```php
add_filter( 'oaf_wptai_freemius_plugins', function( $plugins ) {
    $plugins['XXXX_FS'] = 'sk_XXXXXX';
    return $plugins;
});
```

---

## 2.6 – Plugin Replacement System
- Added support for replacing plugins dynamically on first run.
- Supports hardcoded replacements OR filter-based replacements.
- Supports GridPane, InstaWP, Softaculous, and custom environments.

**Example usage:**
```php
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

---

## 2.5 – License Key Constants System
- Added ability to write license constants to GridPane `user-configs.php`, standard `wp-config.php`, and `.env` files.
- Supports auto-detection of hosting environment.
- Includes structured block markers, backups, and atomic writing.

---

## 2.4 – Improvements & Bug Fixes
- Fixed persistent Welcome Screen issue.
- Added `dashboard_rediscache` (GridPane) to hidden widgets.
- Removed legacy WPLANG support.
- Added automatic download and activation of language packs.
- Added link to the original WordPress.org plugin.
- Updated authorship to reflect new maintainer.

---

## 2.3.2 – Permalink Fix
- Added `flush_rewrite_rules()` to ensure permalink structure applies immediately.
- Fixes issues affecting Bricks Builder and other page builders.

---

## 2.3.1 – Self-Deleting Plugin
- Plugin now removes itself after all tasks complete.
- Leaves no footprint on production sites.

---

## 2.3 – Dashboard Cleanup & Softaculous Removal
- Added additional Softaculous plugin removals.
- Added avatar disabling.
- Improved hidden Screen Options handling.
- Enhanced Welcome Panel persistence behaviour.

---

## 2.2 – WordPress 6.7 Enhancements
- Removed Welcome Screen.
- Removed Pattern Library guides.
- Updated compatibility handling for new editor behaviour.

---

## 2.1 – Softaculous Spam Removal
- Removed additional Softaculous‑installed plugins.

---

## 1.922 – InstaWP Compatibility
- Removed Jetpack (auto-installed by InstaWP).

---

## 1.921 – UK Locale & Timezone
- Set timezone to Europe/London.
- Updated date format to “17th June 2022”.

---

## 1.91 – Early Enhancements
- Added “Disable search engine visibility”.
- Removed Privacy Policy page.
- Updated default category to “General”.

---

## 1.x – Original Plugin (Oh Yeah Devs)
Performed the original installation tasks:
- Removed default posts/pages.
- Removed unused themes.
- Disabled comments and pings.
- Removed default plugins.
- Initial hardening and cleanup.
