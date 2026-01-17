<?php
/**
 * Uninstall script for CitySyncAI
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$settings = [
    'citysyncai_content_type',
    'citysyncai_ai_provider',
    'citysyncai_enable_ai',
    'citysyncai_enable_schema',
    'citysyncai_schema_type',
    'citysyncai_sync_frequency',
    'citysyncai_webhook_url',
    'citysyncai_onboarding_complete'
];

$export = [];

foreach ($settings as $key) {
    $export[$key] = get_option($key);
    delete_option($key);
}

// Optional: Save to file (disabled by default)
// file_put_contents(WP_CONTENT_DIR . '/citysyncai-settings-backup.json', json_encode($export, JSON_PRETTY_PRINT));