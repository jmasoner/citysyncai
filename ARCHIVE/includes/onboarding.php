<?php
/**
 * Onboarding Wizard for CitySyncAI
 * 
 */
/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection PhpUndefinedConstantInspection */
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    if (!get_option('citysyncai_onboarding_complete')) {
        add_dashboard_page('CitySyncAI Setup', 'CitySyncAI Setup', 'manage_options', 'citysyncai-onboarding', 'citysyncai_render_onboarding');
    }
});
add_action('wp_dashboard_setup', function () {
    wp_add_dashboard_widget('citysyncai_widget', 'CitySyncAI Quick Access', 'citysyncai_render_dashboard_widget');
});

function citysyncai_render_dashboard_widget() {
    echo '<p><strong>AI Provider:</strong> ' . esc_html(get_option('citysyncai_ai_provider')) . '</p>';
    echo '<p><strong>Schema Type:</strong> ' . esc_html(get_option('citysyncai_schema_type')) . '</p>';
    echo '<p><a href="' . admin_url('options-general.php?page=citysyncai') . '" class="button">Open Settings</a></p>';
    echo '<p><a href="' . rest_url('citysyncai/v1/export-settings') . '" class="button">Export Settings</a></p>';
}

function citysyncai_render_onboarding() {
    echo '<div class="wrap"><h1>Welcome to CitySyncAI</h1>';
    echo '<p>This wizard will help you configure your AI provider, schema type, and webhook settings.</p>';
    echo '<p><a href="' . admin_url('options-general.php?page=citysyncai') . '" class="button button-primary">Go to Settings</a></p>';
    echo '</div>';
    update_option('citysyncai_onboarding_complete', true);
}