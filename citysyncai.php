<?php
/**
 * Plugin Name: CitySyncAI
 * Description: Dynamically generate SEO-optimized city pages using AI-powered content and schema.
 * Version: 1.0.0
 * Author: John Masoner
 * License: MIT
 */

defined('ABSPATH') || exit;

// 🔹 Load modules
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/page-generator.php';
require_once plugin_dir_path(__FILE__) . 'includes/ai-content-engine.php';
require_once plugin_dir_path(__FILE__) . 'includes/schema-engine.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest-endpoints.php';
require_once plugin_dir_path(__FILE__) . 'schema-sitemap.php';

// 🔹 Shortcodes
add_shortcode('citysyncai', 'citysyncai_render_schema_output');
add_shortcode('citysyncai_block', 'citysyncai_render_ai_block');
add_shortcode('citysyncai_schema', 'citysyncai_render_schema_block');

// 🔹 REST API
add_action('rest_api_init', function () {
    register_rest_route('citysyncai/v1', '/generate', [
        'methods'  => 'POST',
        'callback' => 'citysyncai_rest_generate_content',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('citysyncai/v1', '/schema', [
        'methods'  => 'GET',
        'callback' => 'citysyncai_rest_schema_export',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('citysyncai/v1', '/preview', [
        'methods'  => 'GET',
        'callback' => 'citysyncai_rest_preview_content',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('citysyncai/v1', '/flush-cache', [
        'methods'  => 'POST',
        'callback' => 'citysyncai_rest_flush_cache',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('citysyncai/v1', '/validate-schema', [
        'methods'  => 'GET',
        'callback' => 'citysyncai_rest_validate_schema',
        'permission_callback' => '__return_true',
    ]);
});

// 🔹 Activation hooks
register_activation_hook(__FILE__, function () {
    flush_rewrite_rules();
});
register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});

// 🔹 Frontend JS
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('citysyncai-frontend', plugin_dir_url(__FILE__) . 'assets/citysyncai.js', [], '1.0', true);
    wp_localize_script('citysyncai-frontend', 'CitySyncAI', [
        'rest_url' => esc_url(rest_url('citysyncai/v1/generate')),
    ]);
});

// 🔹 Main output
function citysyncai_render_schema_output() {
    $schema_type  = get_option('citysyncai_schema_type', 'LocalBusiness');
    $enable_ai    = get_option('citysyncai_enable_ai', 'no');
    $provider     = get_option('citysyncai_ai_provider', 'openai');
    $content_type = get_option('citysyncai_content_type', 'overview');

    $template_path = plugin_dir_path(__FILE__) . 'templates/schema-' . strtolower($schema_type) . '.php';
    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo "<!-- Schema template not found: $schema_type -->";
    }

    if ($enable_ai === 'yes') {
        citysyncai_generate_ai_content($provider, $content_type);
    }
}

// 🔹 AI block shortcode
function citysyncai_render_ai_block($atts) {
    $atts = shortcode_atts([
        'city'     => '',
        'type'     => 'overview',
        'provider' => get_option('citysyncai_ai_provider', 'openai'),
    ], $atts);

    return "<div id='citysyncai-block' data-city='{$atts['city']}' data-type='{$atts['type']}' data-provider='{$atts['provider']}'><p>Loading AI content...</p></div>";
}

// 🔹 Schema block shortcode
function citysyncai_render_schema_block($atts) {
    global $post;
    $override = get_post_meta($post->ID ?? 0, '_citysyncai_custom_schema', true);
    if ($override) {
        return "<script type='application/ld+json'>{$override}</script>";
    }

    $schema_type = get_post_meta($post->ID ?? 0, '_citysyncai_schema_type', true) ?: get_option('citysyncai_schema_type', 'LocalBusiness');
    $template_path = plugin_dir_path(__FILE__) . 'templates/schema-' . strtolower($schema_type) . '.php';

    ob_start();
    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo "<!-- Schema template not found: $schema_type -->";
    }
    return ob_get_clean();
}
function citysyncai_render_ai_block($atts) {
    global $post;
    $override = get_post_meta($post->ID ?? 0, '_citysyncai_custom_ai', true);

    if ($override) {
        return "<div class='citysyncai-ai-content'><h3>Custom AI Content</h3><div>{$override}</div></div>";
    }

    $atts = shortcode_atts([
        'city'     => '',
        'type'     => 'overview',
        'provider' => get_option('citysyncai_ai_provider', 'openai'),
    ], $atts);

    return "<div id='citysyncai-block' data-city='{$atts['city']}' data-type='{$atts['type']}' data-provider='{$atts['provider']}'><p>Loading AI content...</p></div>";
}
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'citysyncai-ai-block',
        plugin_dir_url(__FILE__) . 'assets/blocks/ai-block.js',
        ['wp-blocks', 'wp-element', 'wp-components', 'wp-editor'],
        '1.0',
        true
    );

    wp_enqueue_script(
        'citysyncai-schema-block',
        plugin_dir_url(__FILE__) . 'assets/blocks/schema-block.js',
        ['wp-blocks', 'wp-element'],
        '1.0',
        true
    );
});
add_action('admin_notices', function () {
    if (!get_option('citysyncai_onboarding_complete')) {
        echo '<div class="notice notice-info"><p><strong>Welcome to CitySyncAI!</strong> Let’s configure your AI provider, schema type, and webhook. <a href="' . admin_url('options-general.php?page=citysyncai') . '">Start setup</a></p></div>';
    }
});