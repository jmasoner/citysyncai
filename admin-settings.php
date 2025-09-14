<?php
/**
 * Admin Settings Panel for CitySyncAI Plugin
 */

function citysyncai_register_settings() {
    register_setting('citysyncai_options', 'citysyncai_content_type');
    register_setting('citysyncai_options', 'citysyncai_ai_provider');
    register_setting('citysyncai_options', 'citysyncai_openai_key');
    register_setting('citysyncai_options', 'citysyncai_gemini_key');
    register_setting('citysyncai_options', 'citysyncai_claude_key');
    register_setting('citysyncai_options', 'citysyncai_deepseek_key');
    register_setting('citysyncai_options', 'citysyncai_genspark_key');
    register_setting('citysyncai_options', 'citysyncai_grok_key');
    register_setting('citysyncai_options', 'citysyncai_enable_schema');
    register_setting('citysyncai_options', 'citysyncai_sync_frequency');

    add_settings_section('citysyncai_main', 'CitySyncAI Settings', null, 'citysyncai');

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

function citysyncai_ai_provider_field() {
    $provider = get_option('citysyncai_ai_provider', 'openai');
    echo '<select name="citysyncai_ai_provider">
        <option value="openai"' . selected($provider, 'openai', false) . '>OpenAI</option>
        <option value="gemini"' . selected($provider, 'gemini', false) . '>Gemini</option>
        <option value="claude"' . selected($provider, 'claude', false) . '>Claude</option>
        <option value="deepseek"' . selected($provider, 'deepseek', false) . '>Deepseek</option>
        <option value="genspark"' . selected($provider, 'genspark', false) . '>Genspark</option>
        <option value="grok"' . selected($provider, 'grok', false) . '>Grok</option>
    </select>';
}

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

function citysyncai_content_type_field() {
    $value = get_option('citysyncai_content_type', 'overview');
    echo '<select name="citysyncai_content_type">
        <option value="overview"' . selected($value, 'overview', false) . '>Overview</option>
        <option value="services"' . selected($value, 'services', false) . '>Local Services</option>
        <option value="testimonials"' . selected($value, 'testimonials', false) . '>Testimonials</option>
        <option value="custom"' . selected($value, 'custom', false) . '>Custom AI Prompt</option>
    </select>';
}

function citysyncai_schema_field() {
    $value = get_option('citysyncai_enable_schema', false);
    echo '<input type="checkbox" name="citysyncai_enable_schema" value="1"' . checked($value, true, false) . ' />';
}

function citysyncai_sync_field() {
    $value = get_option('citysyncai_sync_frequency', 'manual');
    echo '<select name="citysyncai_sync_frequency">
        <option value="daily"' . selected($value, 'daily', false) . '>Daily</option>
        <option value="weekly"' . selected($value, 'weekly', false) . '>Weekly</option>
        <option value="manual"' . selected($value, 'manual', false) . '>Manual Only</option>
    </select>';
}

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