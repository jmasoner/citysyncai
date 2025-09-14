<?php
/**
 * Admin Settings Panel for CitySyncAI Plugin
 */

function citysyncai_register_settings() {
    // Register all settings
    $settings = [
        'citysyncai_content_type',
        'citysyncai_ai_provider',
        'citysyncai_openai_key',
        'citysyncai_gemini_key',
        'citysyncai_claude_key',
        'citysyncai_deepseek_key',
        'citysyncai_genspark_key',
        'citysyncai_grok_key',
        'citysyncai_enable_schema',
        'citysyncai_sync_frequency',
        'citysyncai_schema_type',
        'citysyncai_enable_ai'
    ];

    foreach ($settings as $setting) {
        register_setting('citysyncai_options', $setting);
    }

    // Create a single section
    add_settings_section('citysyncai_main', 'CitySyncAI Settings', null, 'citysyncai');

    // Add fields
    add_settings_field('schema_type', 'Schema Type', 'citysyncai_schema_type_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('enable_ai', 'Enable AI Content', 'citysyncai_enable_ai_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('ai_provider', 'AI Provider', 'citysyncai_ai_provider_field', 'citysyncai', 'citysyncai_main');

    add_settings_field('openai_key', 'OpenAI API Key', 'citysyncai_openai_key_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('gemini_key', 'Gemini API Key', 'citysyncai_gemini_key_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('claude_key', 'Claude API Key', 'citysyncai_claude_key_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('deepseek_key', 'Deepseek API Key', 'citysyncai_deepseek_key_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('genspark_key', 'Genspark API Key', 'citysyncai_genspark_key_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('grok_key', 'Grok API Key', 'citysyncai_grok_key_field', 'citysyncai', 'citysyncai_main');

    add_settings_field('content_type', 'Default Content Type', 'citysyncai_content_type_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('enable_schema', 'Enable Gemini SEO Schema', 'citysyncai_schema_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('sync_frequency', 'Sync Frequency', 'citysyncai_sync_field', 'citysyncai', 'citysyncai_main');
}
add_action('admin_init', 'citysyncai_register_settings');

// Field renderers
function citysyncai_schema_type_field() {
    $schema = get_option('citysyncai_schema_type', 'LocalBusiness');
    echo '<select name="citysyncai_schema_type">';
    foreach (['LocalBusiness', 'Event', 'Service', 'Review'] as $type) {
        echo '<option value="' . $type . '"' . selected($schema, $type, false) . '>' . $type . '</option>';
    }
    echo '</select>';
}

function citysyncai_enable_ai_field() {
    $enabled = get_option('citysyncai_enable_ai', 'no');
    echo '<label><input type="checkbox" name="citysyncai_enable_ai" value="yes"' . checked($enabled, 'yes', false) . '> Enable AI Content</label>';
}

function citysyncai_ai_provider_field() {
    $provider = get_option('citysyncai_ai_provider', 'openai');
    echo '<select name="citysyncai_ai_provider">';
    foreach (['openai', 'gemini', 'claude', 'deepseek', 'genspark', 'grok'] as $agent) {
        echo '<option value="' . $agent . '"' . selected($provider, $agent, false) . '>' . ucfirst($agent) . '</option>';
    }
    echo '</select>';
}

// API key fields
function citysyncai_openai_key_field() {
    $key = get_option('citysyncai_openai_key', '');
    echo '<input type="text" name="citysyncai_openai_key" value="' . esc_attr($key) . '" />';
}
function citysyncai_gemini_key_field() {
    $key = get_option('citysyncai_gemini_key', '');
    echo '<input type="text" name="citysyncai_gemini_key" value="' . esc_attr($key) . '" />';
}
function citysyncai_claude_key_field() {
    $key = get_option('citysyncai_claude_key', '');
    echo '<input type="text" name="citysyncai_claude_key" value="' . esc_attr($key) . '" />';
}
function citysyncai_deepseek_key_field() {
    $key = get_option('citysyncai_deepseek_key', '');
    echo '<input type="text" name="citysyncai_deepseek_key" value="' . esc_attr($key) . '" />';
}
function citysyncai_genspark_key_field() {
    $key = get_option('citysyncai_genspark_key', '');
    echo '<input type="text" name="citysyncai_genspark_key" value="' . esc_attr($key) . '" />';
}
function citysyncai_grok_key_field() {
    $key = get_option('citysyncai_grok_key', '');
    echo '<input type="text" name="citysyncai_grok_key" value="' . esc_attr($key) . '" />';
}

// Other fields
function citysyncai_content_type_field() {
    $value = get_option('citysyncai_content_type', 'overview');
    echo '<select name="citysyncai_content_type">';
    foreach (['overview', 'services', 'testimonials', 'custom'] as $type) {
        echo '<option value="' . $type . '"' . selected($value, $type, false) . '>' . ucfirst($type) . '</option>';
    }
    echo '</select>';
}

function citysyncai_schema_field() {
    $value = get_option('citysyncai_enable_schema', false);
    echo '<input type="checkbox" name="citysyncai_enable_schema" value="1"' . checked($value, true, false) . ' />';
}

function citysyncai_sync_field() {
    $value = get_option('citysyncai_sync_frequency', 'manual');
    echo '<select name="citysyncai_sync_frequency">';
    foreach (['daily', 'weekly', 'manual'] as $freq) {
        echo '<option value="' . $freq . '"' . selected($value, $freq, false) . '>' . ucfirst($freq) . '</option>';
    }
    echo '</select>';
}

// Admin page
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
    ?>
    <div class="wrap">
        <h1>CitySyncAI Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('citysyncai_options');
            do_settings_sections('citysyncai');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}