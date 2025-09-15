<?php
/**
 * Admin Settings Panel for CitySyncAI Plugin
 */

defined('ABSPATH') || exit;

function citysyncai_register_settings() {
    $settings = [
        'citysyncai_content_type',
        'citysyncai_ai_provider',
        'citysyncai_enable_ai',
        'citysyncai_enable_schema',
        'citysyncai_schema_type',
        'citysyncai_sync_frequency',
        'citysyncai_webhook_url'
    ];

    foreach ($settings as $setting) {
        register_setting('citysyncai_options', $setting);
    }

    add_settings_section('citysyncai_main', 'CitySyncAI Settings', null, 'citysyncai');

    add_settings_field('schema_type', 'Schema Type', function () {
        $schema = get_option('citysyncai_schema_type', 'LocalBusiness');
        echo '<select name="citysyncai_schema_type">';
        foreach (['LocalBusiness', 'Event', 'Service', 'Review'] as $type) {
            echo '<option value="' . $type . '"' . selected($schema, $type, false) . '>' . $type . '</option>';
        }
        echo '</select>';
    }, 'citysyncai', 'citysyncai_main');

    add_settings_field('enable_ai', 'Enable AI Content', function () {
        $enabled = get_option('citysyncai_enable_ai', 'no');
        echo '<label><input type="checkbox" name="citysyncai_enable_ai" value="yes"' . checked($enabled, 'yes', false) . '> Enable AI Content</label>';
    }, 'citysyncai', 'citysyncai_main');

    add_settings_field('ai_provider', 'AI Provider', function () {
        $provider = get_option('citysyncai_ai_provider', 'openai');
        echo '<select name="citysyncai_ai_provider">';
        foreach (['openai', 'gemini', 'claude', 'deepseek', 'genspark', 'grok'] as $agent) {
            echo '<option value="' . $agent . '"' . selected($provider, $agent, false) . '>' . ucfirst($agent) . '</option>';
        }
        echo '</select>';
    }, 'citysyncai', 'citysyncai_main');

    add_settings_field('content_type', 'Default Content Type', function () {
        $value = get_option('citysyncai_content_type', 'overview');
        echo '<select name="citysyncai_content_type">';
        foreach (['overview', 'services', 'testimonials', 'custom'] as $type) {
            echo '<option value="' . $type . '"' . selected($value, $type, false) . '>' . ucfirst($type) . '</option>';
        }
        echo '</select>';
    }, 'citysyncai', 'citysyncai_main');

    add_settings_field('enable_schema', 'Enable Schema Injection', function () {
        $value = get_option('citysyncai_enable_schema', false);
        echo '<input type="checkbox" name="citysyncai_enable_schema" value="1"' . checked($value, true, false) . ' />';
    }, 'citysyncai', 'citysyncai_main');

    add_settings_field('sync_frequency', 'Sync Frequency', function () {
        $value = get_option('citysyncai_sync_frequency', 'manual');
        echo '<select name="citysyncai_sync_frequency">';
        foreach (['daily', 'weekly', 'manual'] as $freq) {
            echo '<option value="' . $freq . '"' . selected($value, $freq, false) . '>' . ucfirst($freq) . '</option>';
        }
        echo '</select>';
    }, 'citysyncai', 'citysyncai_main');

    add_settings_field('webhook_url', 'Webhook URL', function () {
        $url = get_option('citysyncai_webhook_url', '');
        echo '<input type="url" name="citysyncai_webhook_url" value="' . esc_attr($url) . '" />';
    }, 'citysyncai', 'citysyncai_main');

    // Preview tab
    add_settings_section('citysyncai_preview_section', 'AI Preview', function () {
        $provider = get_option('citysyncai_ai_provider', 'openai');
        $type     = get_option('citysyncai_content_type', 'overview');
        $enabled  = get_option('citysyncai_enable_ai', 'no');

        if ($enabled === 'yes') {
            ob_start();
            citysyncai_generate_ai_content($provider, $type);
            $output = ob_get_clean();
            echo '<div style="border:1px solid #ccc;padding:10px;background:#f9f9f9;">' . $output . '</div>';
        } else {
            echo '<p><em>AI content is disabled.</em></p>';
        }
    }, 'citysyncai');

    // Schema validation tab
    add_settings_section('citysyncai_schema_validation', 'Schema Validation', function () {
        $response = wp_remote_get(rest_url('citysyncai/v1/validate-schema'));
        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (!empty($data['error'])) {
            echo "<p><strong>Error:</strong> {$data['error']}</p>";
            return;
        }

        echo "<p><strong>Schema Type:</strong> {$data['schema_type']}</p>";
        echo "<p><strong>Valid JSON-LD:</strong> " . ($data['valid_jsonld'] ? '✅ Yes' : '❌ No') . "</p>";
        echo "<p><strong>Script Tag Present:</strong> " . ($data['has_script_tag'] ? '✅ Yes' : '❌ No') . "</p>";
        echo "<details><summary>View Raw Markup</summary><pre>" . esc_html($data['markup']) . "</pre></details>";
        echo '<p><a href="https://validator.schema.org/" target="_blank" class="button">Open Schema Validator</a></p>';
    }, 'citysyncai');
}
add_action('admin_init', 'citysyncai_register_settings');

function citysyncai_add_settings_page() {
    add_options_page(
        'CitySyncAI Settings',
        'CitySyncAI',
        'manage_options',
        'citysyncai',
        'citysyncai_render_settings_page'
    );
}
add_action('admin_menu', 'citysyncai_add_settings_page');

function citysyncai_render_settings_page() {
    echo '<div class="wrap"><h1>CitySyncAI Settings</h1><form method="post" action="options.php">';
    settings_fields('citysyncai_options');
    do_settings_sections('citysyncai');
    submit_button();
    echo '</form></div>';
}