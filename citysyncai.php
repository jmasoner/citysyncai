<?php
/**
 * Plugin Name: CitySyncAI
 * Description: Dynamically generate SEO-optimized city pages using AI-powered content.
 * Version: 1.0.0
 * Author: John Masoner
 * License: MIT
 */

defined('ABSPATH') || exit;

// Load core modules
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/page-generator.php';
require_once plugin_dir_path(__FILE__) . 'includes/ai-content-engine.php';
require_once plugin_dir_path(__FILE__) . 'includes/schema.php';

// Register shortcode
add_shortcode('citysyncai', 'citysyncai_shortcode_handler');

// Register REST API
add_action('rest_api_init', function () {
    register_rest_route('citysyncai/v1', '/generate', [
        'methods' => 'POST',
        'callback' => 'citysyncai_rest_generate',
        'permission_callback' => '__return_true',
    ]);
});