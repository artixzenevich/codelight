<?php
/**
 * Plugin Name:       Codelight
 * Description:       A plugin for WordPress to add syntax highlighting with Highlight.js to code blocks, customizable for specific page types and entries.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            Artik Zenevich
 * Author URI:        https://github.com/artixzenevich
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       codelight
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue admin-specific assets (scripts and styles)
function codelight_admin_enqueue_assets($hook_suffix) {
    // Load assets only on the plugin settings page
    if ($hook_suffix === 'settings_page_codelight') {
        $settings = get_option('codelight_settings', ['theme' => 'default']);

        // Enqueue the admin script and style for theme selection
        wp_enqueue_script(
            'codelight-admin',
            plugin_dir_url(__FILE__) . 'assets/admin-script.js',
            [],
            '1.0.0',
            true
        );

		wp_enqueue_style(
			'codelight-admin-style',
			plugin_dir_url(__FILE__) . 'assets/admin-style.css'
		);

        // Enqueue Highlight.js library
        wp_enqueue_script(
            'codelight-highlight',
            plugin_dir_url(__FILE__) . 'assets/highlight/highlight.min.js',
            [],
            '11.9.0',
            true
        );

        // Localize settings for use in admin scripts
        wp_localize_script('codelight-admin', 'codelightSettings', [
            'themePath' => plugin_dir_url(__FILE__) . 'assets/highlight/styles/',
            'currentTheme' => $settings['theme'] ?? 'default',
        ]);

        // Enqueue the CSS for the current Highlight.js theme
        wp_enqueue_style(
            'codelight-highlight-theme',
            plugin_dir_url(__FILE__) . 'assets/highlight/styles/' . esc_attr($settings['theme']) . '.min.css',
            [],
            '11.9.0'
        );
    }
}

add_action('admin_enqueue_scripts', 'codelight_admin_enqueue_assets');

// Render the settings page for the plugin
function codelight_settings_page() {
    $logo_url = plugin_dir_url(__FILE__) . 'assets/img/logo.png';
    $form_action = admin_url('options.php');
    ?>
    <div class="wrap">
        <!-- Display plugin logo -->
        <img src="<?php echo esc_url($logo_url); ?>" width="450" height="65" alt="Codelight Logo">
        <!-- Display settings form -->
        <form method="post" action="<?php echo esc_url($form_action); ?>">
            <?php
            settings_fields('codelight_settings_group');
            do_settings_sections('codelight');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Render the theme selection dropdown in the settings
function codelight_theme_field_render() {
    $settings = get_option('codelight_settings', ['theme' => 'default']);
    $current_theme = $settings['theme'] ?? 'default';
    $themes = glob(plugin_dir_path(__FILE__) . 'assets/highlight/styles/*.min.css');
    ?>

    <select id="codelight-theme-selector" name="codelight_settings[theme]">
        <?php foreach ($themes as $theme_file): 
            $theme_name = basename($theme_file, '.min.css');
        ?>
            <option value="<?php echo esc_attr($theme_name); ?>" <?php selected($theme_name, $current_theme); ?>>
                <?php echo esc_html($theme_name); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div id="codelight-preview-container">
        <pre><code class="preview-code">class MyClass {
  public static myValue: string;
  constructor(init: string) {
    this.myValue = init;
  }
}
import fs = require("fs");
module MyModule {
  export interface MyInterface extends Other {
    myProperty: any;
  }
}
declare magicNumber number;
myArray.forEach(() => { }); // fat arrow syntax</code></pre>
    </div>

    <?php
}

// Render the post types selection checkboxes
function codelight_post_types_field_render() {
    $settings = get_option('codelight_settings', ['post_types' => ['post', 'page']]);
    $selected_post_types = $settings['post_types'] ?? ['post', 'page'];
    $available_post_types = get_post_types(['public' => true], 'objects');
    ?>

    <?php foreach ($available_post_types as $post_type): ?>
        <label>
            <input type="checkbox" name="codelight_settings[post_types][]" 
                   value="<?php echo esc_attr($post_type->name); ?>" 
                   <?php checked(in_array($post_type->name, $selected_post_types, true)); ?>>
            <?php echo esc_html($post_type->label); ?>
        </label>
        <br>
    <?php endforeach; ?>

    <?php
}

// Register plugin settings and fields
function codelight_register_settings() {
    register_setting('codelight_settings_group', 'codelight_settings');

    add_settings_section(
        'codelight_main_section',
        __('Codelight Settings', 'codelight'),
        '__return_false',
        'codelight'
    );

    add_settings_field(
        'codelight_theme',
        __('Highlight.js Theme', 'codelight'),
        'codelight_theme_field_render',
        'codelight',
        'codelight_main_section'
    );

    add_settings_field(
        'codelight_post_types',
        __('Post Types', 'codelight'),
        'codelight_post_types_field_render',
        'codelight',
        'codelight_main_section'
    );
}
add_action('admin_init', 'codelight_register_settings');

// Add the settings page to the WordPress admin menu
function codelight_add_settings_page() {
    add_options_page(
        __('Codelight Settings', 'codelight'),
        __('Codelight', 'codelight'),
        'manage_options',
        'codelight',
        'codelight_settings_page'
    );
}
add_action('admin_menu', 'codelight_add_settings_page');

// Register the block for the plugin
function create_block_codelight_block_init() {
    register_block_type( __DIR__ . '/build' );
}

add_action( 'init', 'create_block_codelight_block_init' );

// Enqueue assets for frontend pages
function codelight_enqueue_assets() {
    $settings = get_option('codelight_settings', [
        'theme' => 'default',
        'post_types' => ['post', 'page'],
    ]);

    // Load assets only for specified post types
    if (is_singular($settings['post_types'])) {
        wp_enqueue_script(
            'codelight-highlight',
            plugin_dir_url(__FILE__) . 'assets/highlight/highlight.min.js',
            [],
            '11.9.0',
            true
        );

        wp_enqueue_style(
            'codelight-highlight-theme',
            plugin_dir_url(__FILE__) . 'assets/highlight/styles/' . esc_attr($settings['theme']) . '.min.css',
            [],
            '11.9.0'
        );

        // Initialize syntax highlighting on page load
        wp_add_inline_script(
            'codelight-highlight',
            'document.addEventListener("DOMContentLoaded", function() { hljs.highlightAll(); });'
        );
    }
}

add_action('wp_enqueue_scripts', 'codelight_enqueue_assets');
