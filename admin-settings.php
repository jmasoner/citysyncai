<?php
function citysyncai_register_settings() {
    register_setting('citysyncai_options', 'citysyncai_content_type');
    register_setting('citysyncai_options', 'citysyncai_enable_schema');
    register_setting('citysyncai_options', 'citysyncai_sync_frequency');

    add_settings_section('citysyncai_main', 'CitySyncAI Settings', null, 'citysyncai');

    add_settings_field('content_type', 'Default Content Type', 'citysyncai_content_type_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('enable_schema', 'Enable Gemini SEO Schema', 'citysyncai_schema_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('sync_frequency', 'Sync Frequency', 'citysyncai_sync_field', 'citysyncai', 'citysyncai_main');
}
add_action('admin_init', 'citysyncai_register_settings');

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