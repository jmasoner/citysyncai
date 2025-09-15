<?php
/**
 * Plugin Name: CitySyncAI
 * Description: Generate SEO-optimized city pages using AI and structured schema.
 * Version: 1.0.0
 * Author: John Masoner
 * Text Domain: citysyncai
 * Domain Path: /languages
 * License: MIT
 */

defined('ABSPATH') || exit;

// 🔹 Constants
define('CITYSYNCAI_VERSION', '1.0.0');
define('CITYSYNCAI_DIR', plugin_dir_path(__FILE__));
define('CITYSYNCAI_URL', plugin_dir_url(__FILE__));

// 🔹 Includes
require_once CITYSYNCAI_DIR . 'includes/admin-settings.php';
require_once CITYSYNCAI_DIR . 'includes/ai-content-engine.php';
require_once CITYSYNCAI_DIR . 'includes/schema-engine.php';
require_once CITYSYNCAI_DIR . 'includes/rest-endpoints.php';
require_once CITYSYNCAI_DIR . 'includes/log-engine.php';
require_once CITYSYNCAI_DIR . 'includes/onboarding.php';
require_once CITYSYNCAI_DIR . 'includes/readme-generator.php';

add_action('plugins_loaded', function () {
    load_plugin_textdomain('citysyncai', false, dirname(plugin_basename(__FILE__)) . '/languages');
});
// 🔹 Assets
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('citysyncai-admin', CITYSYNCAI_URL . 'assets/admin-script.js', ['jquery'], CITYSYNCAI_VERSION, true);
    wp_enqueue_style('citysyncai-admin-style', CITYSYNCAI_URL . 'assets/css/admin-stule.css', [], CITYSYNCAI_VERSION);
});

// 🔹 Shortcodes
add_shortcode('citysyncai', 'citysyncai_render_combined');
add_shortcode('citysyncai_block', 'citysyncai_render_ai_block');
add_shortcode('citysyncai_schema', 'citysyncai_render_schema_block');

if (!function_exists('citysyncai_render_ai_block')) {
    function citysyncai_render_ai_block($atts = []) {
        $provider = get_option('citysyncai_ai_provider', 'openai');
        $type     = get_option('citysyncai_content_type', 'overview');
        ob_start();
        citysyncai_generate_ai_content($provider, $type);
        return ob_get_clean();
    }
}

if (!function_exists('citysyncai_render_schema_block')) {
    function citysyncai_render_schema_block($atts = []) {
        $schema_type = get_option('citysyncai_schema_type', 'LocalBusiness');
        $template = CITYSYNCAI_DIR . 'templates/schema-' . strtolower($schema_type) . '.php';
        ob_start();
        if (file_exists($template)) {
            include $template;
        } else {
            echo "<!-- Schema template not found: $schema_type -->";
        }
        return ob_get_clean();
    }
}

if (!function_exists('citysyncai_render_combined')) {
    function citysyncai_render_combined($atts = []) {
        return citysyncai_render_ai_block($atts) . citysyncai_render_schema_block($atts);
    }
}

// 🔹 Gutenberg Blocks
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'citysyncai-ai-block',
        CITYSYNCAI_URL . 'assets/blocks/ai-blocks.js',
        ['wp-blocks', 'wp-element', 'wp-components', 'wp-editor'],
        CITYSYNCAI_VERSION,
        true
    );

    wp_enqueue_script(
        'citysyncai-schema-block',
        CITYSYNCAI_URL . 'assets/blocks/schema-blocks.js',
        ['wp-blocks', 'wp-element'],
        CITYSYNCAI_VERSION,
        true
    );
});

// 🔹 Activation Hook
register_activation_hook(__FILE__, function () {
    update_option('citysyncai_onboarding_complete', false);
});