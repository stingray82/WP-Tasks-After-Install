# WP Tasks After Install

A modern, fully‑automated WordPress setup tool designed to clean, optimise, configure, and secure a fresh WordPress installation.  
This is a heavily enhanced and actively developed version of the original *WP Tasks After Install* plugin by Oh Yeah Devs.

---

## 🚀 What This Plugin Does

Upon activation, WP Tasks After Install automatically performs **dozens of essential setup and cleanup tasks**, saving time and ensuring every site begins with a clean, optimised baseline.

### Key Features

### 🧹 Cleanup & Hardening

- Removes *Hello World*, default pages, privacy page
- Removes Akismet, Hello Dolly, Jetpack, Softaculous spam plugins
- Removes unnecessary themes
- Deletes `readme.html` and `wp-config-sample.php`
- Disables comments, pings, avatars, and search engine indexing
- Disables WordPress 6.7+ Welcome Guide & Pattern Library

### ⚙️ System Optimisation

- Sets timezone to **Europe/London**
- Sets UK date format (e.g., _17th June 2022_)
- Installs and activates the `en_GB` language pack
- Sets permalink structure to `/%postname%/`
- Fully hides dashboard clutter including:
  - Quick Draft
  - Site Health
  - Activity
  - Welcome Panel
  - GridPane Nginx cache widget
- Disables all default image sizes & big image scaling
- Standardises media, upload, and thumbnail behaviour

### 🔌 Plugin Management

- Automatically replaces plugins using filters (v2.6+)
- Supports installing or replacing plugins from ZIP URLs
- Host‑compatible: GridPane, InstaWP, Softaculous, cPanel, etc.

### 🔐 Licensing & Configuration Automation

- Auto‑writes license constants to:
  - `user-configs.php` (GridPane)
  - `wp-config.php`
  - `.env` variables (optional)
- Auto‑activates Freemius‑powered plugins (v2.7+)  
  Example:

```php
add_filter( 'oaf_wptai_freemius_plugins', function( $plugins ) {
    $plugins['MYPLUGIN_FS'] = 'sk_XXXXXX';
    return $plugins;
});
```

### 🔄 Plugin Replacement System

Replace auto-installed or undesired plugins:

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

### 🧨 Self-Destruct Mode

From **v2.3.1**, the plugin automatically **deletes itself** after finishing all tasks, leaving no footprint.

---

## 📦 Installation

1. Upload the plugin or add via Plugin Installer.
2. Activate it once.
3. The plugin performs all tasks automatically.
4. It then deactivates—or fully removes itself.

---

## 👥 Contributors  

- **oabadfol**  
- **valhallawp**  
- **ohyeahdev**  
- **fernandoaureonet**  
- **reallyusefulplugins**  
- **stingray82** (current maintainer)

---

## 📝 License

GPLv2 or later.  
Original plugin: https://wordpress.org/plugins/wp-tasks-after-install  
Modified + maintained version: https://github.com/stingray82/WP-Tasks-After-Install

---

## 🧭 Roadmap / Ideas

The plugin is currently considered feature-complete.  
If you have suggestions, improvements, or automation ideas, open an Issue on GitHub.

---

📜 **Full Changelog:**  
https://github.com/stingray82/WP-Tasks-After-Install/blob/main/changelog.md
