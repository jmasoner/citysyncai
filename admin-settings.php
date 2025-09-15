<?php
/**
 * Admin Settings Panel for CitySyncAI
 */

defined('ABSPATH') || exit;

add_action('admin_menu', 'citysyncai_admin_menu');
add_action('admin_init', 'citysyncai_register_settings');

function citysyncai_admin_menu() {
    add_options_page(
        'CitySyncAI Settings',
        'CitySyncAI',
        'manage_options',
        'citysyncai',
        'citysyncai_settings_page'
    );
}

function citysyncai_register_settings() {
    // Core Settings
    register_setting('citysyncai', 'citysyncai_schema_type');
    register_setting('citysyncai', 'citysyncai_enable_ai');
    register_setting('citysyncai', 'citysyncai_ai_provider');
    register_setting('citysyncai', 'citysyncai_content_type');

    // Section: Schema Settings
    add_settings_section('citysyncai_schema_section', 'Schema Settings', null, 'citysyncai');

    add_settings_field('citysyncai_schema_type', 'Schema Type', function () {
        $value = get_option('citysyncai_schema_type', 'LocalBusiness');
        echo '<select name="citysyncai_schema_type">';
        foreach (['LocalBusiness', 'Event', 'Service', 'Review'] as $type) {
            echo "<option value='$type'" . selected($value, $type, false) . ">$type</option>";
        }
        echo '</select>';
    }, 'citysyncai', 'citysyncai_schema_section');

    // Section: AI Settings
    add_settings_section('citysyncai_ai_section', 'AI Settings', null, 'citysyncai');

    add_settings_field('citysyncai_enable_ai', 'Enable AI Content', function () {
        $value = get_option('citysyncai_enable_ai', 'no');
        echo '<select name="citysyncai_enable_ai">';
        foreach (['yes', 'no'] as $option) {
            echo "<option value='$option'" . selected($value, $option, false) . ">$option</option>";
        }
        echo '</select>';
    }, 'citysyncai', 'citysyncai_ai_section');

    add_settings_field('citysyncai_ai_provider', 'AI Provider', function () {
        $value = get_option('citysyncai_ai_provider', 'openai');
        $providers = ['openai', 'gemini', 'claude', 'deepseek', 'genspark', 'grok'];
        echo '<select name="citysyncai_ai_provider">';
        foreach ($providers as $provider) {
            echo "<option value='$provider'" . selected($value, $provider, false) . ">" . ucfirst($provider) . "</option>";
        }
        echo '</select>';
    }, 'citysyncai', 'citysyncai_ai_section');

    add_settings_field('citysyncai_content_type', 'Content Type', function () {
        $value = get_option('citysyncai_content_type', 'overview');
        $types = ['overview', 'services', 'testimonials', 'faq', 'contact'];
        echo '<select name="citysyncai_content_type">';
        foreach ($types as $type) {
            echo "<option value='$type'" . selected($value, $type, false) . ">" . ucfirst($type) . "</option>";
        }
        echo '</select>';
    }, 'citysyncai', 'citysyncai_ai_section');

    // Section: AI Preview
    add_settings_section('citysyncai_preview_section', 'AI Preview', function () {
        $provider     = get_option('citysyncai_ai_provider', 'openai');
        $content_type = get_option('citysyncai_content_type', 'overview');
        $enable_ai    = get_option('citysyncai_enable_ai', 'no');

        if ($enable_ai === 'yes') {
            echo "<p>Preview of AI-generated content using <strong>$provider</strong> for <strong>$content_type</strong>:</p>";
            citysyncai_generate_ai_content($provider, $content_type);
        } else {
            echo "<p>AI content is currently disabled.</p>";
        }
    }, 'citysyncai');
}

function citysyncai_settings_page() {
    echo '<div class="wrap">';
    echo '<h1>CitySyncAI Settings</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('citysyncai');
    do_settings_sections('citysyncai');
    submit_button();
    echo '</form>';
    echo '</div>';
}