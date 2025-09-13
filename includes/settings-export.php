<?php
function citysyncai_export_settings() {
    $options = [
        'citysyncai_content_type',
        'citysyncai_enable_schema',
        'citysyncai_sync_frequency',
        'citysyncai_ai_provider',
        'citysyncai_openai_key',
        'citysyncai_gemini_key',
        'citysyncai_claude_key',
    ];

    $export = [];
    foreach ($options as $key) {
        $export[$key] = get_option($key);
    }

    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="citysyncai-settings.json"');
    echo json_encode($export);
    exit;
}