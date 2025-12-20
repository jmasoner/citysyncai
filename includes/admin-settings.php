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
        'citysyncai_webhook_url',
        'citysyncai_openai_key',
        'citysyncai_gemini_key',
        'citysyncai_claude_key',
        'citysyncai_deepseek_key',
        'citysyncai_genspark_key',
        'citysyncai_grok_key',
        'citysyncai_gemini_key_2',
        'citysyncai_use_multi_account',
        'citysyncai_gemini_model'
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
        $provider = get_option('citysyncai_ai_provider', 'gemini');
        echo '<select name="citysyncai_ai_provider">';
        $providers = [
            'gemini' => 'Gemini (Recommended)',
            'deepseek' => 'DeepSeek (Very Affordable)',
            'openai' => 'OpenAI',
            'claude' => 'Claude',
            'genspark' => 'Genspark',
            'grok' => 'Grok',
        ];
        foreach ($providers as $key => $label) {
            echo '<option value="' . esc_attr($key) . '"' . selected($provider, $key, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">Gemini with paid accounts can handle large-scale generation efficiently.</p>';
    }, 'citysyncai', 'citysyncai_main');

    add_settings_field('gemini_model', 'Gemini Model', function () {
        $model = get_option('citysyncai_gemini_model', 'gemini-1.5-flash');
        echo '<select name="citysyncai_gemini_model">';
        $models = [
            'gemini-1.5-flash' => 'Gemini 1.5 Flash (Fastest, Most Cost-Effective)',
            'gemini-1.5-pro' => 'Gemini 1.5 Pro (Higher Quality)',
            'gemini-pro' => 'Gemini Pro (Legacy)',
        ];
        foreach ($models as $key => $label) {
            echo '<option value="' . esc_attr($key) . '"' . selected($model, $key, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description"><strong>Flash recommended</strong> for bulk generation - 40x cheaper and faster!</p>';
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
        echo '<input type="url" name="citysyncai_webhook_url" value="' . esc_attr($url) . '" class="regular-text" />';
    }, 'citysyncai', 'citysyncai_main');

    // API Keys Section
    add_settings_section('citysyncai_api_keys', 'API Keys', function () {
        echo '<p>Enter your API keys for the AI providers you want to use. Keys are stored securely in your WordPress database.</p>';
        echo '<p><strong>Recommended:</strong> Start with Google Gemini (free tier) - Get your key at <a href="https://makersuite.google.com/app/apikey" target="_blank">Google AI Studio</a></p>';
    }, 'citysyncai');

    add_settings_field('openai_key', 'OpenAI API Key', function () {
        $key = get_option('citysyncai_openai_key', '');
        echo '<input type="password" name="citysyncai_openai_key" value="' . esc_attr($key) . '" class="regular-text" />';
        echo '<p class="description">Get your key at <a href="https://platform.openai.com/api-keys" target="_blank">OpenAI Platform</a></p>';
    }, 'citysyncai', 'citysyncai_api_keys');

    add_settings_field('gemini_key', 'Google Gemini API Key (Primary)', function () {
        $key = get_option('citysyncai_gemini_key', '');
        echo '<input type="password" name="citysyncai_gemini_key" value="' . esc_attr($key) . '" class="regular-text" />';
        echo '<p class="description"><strong>Primary account.</strong> Get your key at <a href="https://makersuite.google.com/app/apikey" target="_blank">Google AI Studio</a></p>';
    }, 'citysyncai', 'citysyncai_api_keys');

    add_settings_field('gemini_key_2', 'Google Gemini API Key (Secondary - Optional)', function () {
        $key = get_option('citysyncai_gemini_key_2', '');
        echo '<input type="password" name="citysyncai_gemini_key_2" value="' . esc_attr($key) . '" class="regular-text" />';
        echo '<p class="description">Add a second API key to double your capacity. Plugin will rotate between keys automatically.</p>';
    }, 'citysyncai', 'citysyncai_api_keys');

    add_settings_field('use_multi_account', 'Enable Multi-Account Rotation', function () {
        $enabled = get_option('citysyncai_use_multi_account', 'no');
        $has_second_key = !empty(get_option('citysyncai_gemini_key_2', ''));
        $disabled = !$has_second_key ? 'disabled' : '';
        $description = $has_second_key 
            ? 'Automatically rotate between both API keys to maximize throughput' 
            : 'Enable this after adding a second API key above';
        echo '<label><input type="checkbox" name="citysyncai_use_multi_account" value="yes"' . checked($enabled, 'yes', false) . ' ' . $disabled . '> Enable Key Rotation</label>';
        echo '<p class="description">' . esc_html($description) . '</p>';
    }, 'citysyncai', 'citysyncai_api_keys');

    add_settings_field('claude_key', 'Anthropic Claude API Key', function () {
        $key = get_option('citysyncai_claude_key', '');
        echo '<input type="password" name="citysyncai_claude_key" value="' . esc_attr($key) . '" class="regular-text" />';
        echo '<p class="description">Get your key at <a href="https://console.anthropic.com/" target="_blank">Anthropic Console</a></p>';
    }, 'citysyncai', 'citysyncai_api_keys');

    add_settings_field('deepseek_key', 'DeepSeek API Key', function () {
        $key = get_option('citysyncai_deepseek_key', '');
        echo '<input type="password" name="citysyncai_deepseek_key" value="' . esc_attr($key) . '" class="regular-text" />';
        echo '<p class="description"><strong>Very affordable!</strong> Get your key at <a href="https://platform.deepseek.com/" target="_blank">DeepSeek Platform</a> ($0.14 per 1M tokens)</p>';
    }, 'citysyncai', 'citysyncai_api_keys');

    add_settings_field('genspark_key', 'Genspark API Key', function () {
        $key = get_option('citysyncai_genspark_key', '');
        echo '<input type="password" name="citysyncai_genspark_key" value="' . esc_attr($key) . '" class="regular-text" />';
    }, 'citysyncai', 'citysyncai_api_keys');

    add_settings_field('grok_key', 'Grok API Key', function () {
        $key = get_option('citysyncai_grok_key', '');
        echo '<input type="password" name="citysyncai_grok_key" value="' . esc_attr($key) . '" class="regular-text" />';
        echo '<p class="description">Get your key at <a href="https://x.ai/api" target="_blank">x.ai API</a></p>';
    }, 'citysyncai', 'citysyncai_api_keys');

    // AI Preview
    add_settings_section('citysyncai_preview_section', 'AI Preview', function () {
        $provider = get_option('citysyncai_ai_provider', 'gemini');
        $type     = get_option('citysyncai_content_type', 'overview');
        $enabled  = get_option('citysyncai_enable_ai', 'no');
        $api_key  = get_option('citysyncai_' . $provider . '_key', '');

        if ($enabled === 'yes') {
            if (empty($api_key)) {
                echo '<div style="border:1px solid #ff6b6b;padding:10px;background:#ffe0e0;">';
                echo '<p><strong>⚠️ API Key Required:</strong> Please configure your ' . ucfirst($provider) . ' API key above to generate preview content.</p>';
                echo '</div>';
            } else {
                echo '<p><em>Generating preview... This may take a few seconds.</em></p>';
                ob_start();
                citysyncai_generate_ai_content($provider, $type, 'Sample City, TX');
                $output = ob_get_clean();
                echo '<div style="border:1px solid #ccc;padding:10px;background:#f9f9f9;max-height:400px;overflow-y:auto;">' . $output . '</div>';
            }
        } else {
            echo '<p><em>AI content is disabled. Enable it above to see preview.</em></p>';
        }
    }, 'citysyncai');

    // Schema Validation
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
    echo '<div class="wrap"><h1>CitySyncAI Settings</h1>';
    $screen = get_current_screen();
    $screen->add_help_tab([
        'id'      => 'citysyncai_overview',
        'title'   => 'Overview',
        'content' => '<p>CitySyncAI helps you generate SEO-optimized city pages using AI and structured schema.</p>',
    ]);
    $screen->add_help_tab([
        'id'      => 'citysyncai_schema',
        'title'   => 'Schema',
        'content' => '<p>Select your schema type and optionally override it per post. Schema is injected as JSON-LD.</p>',
    ]);
    $screen->add_help_tab([
        'id'      => 'citysyncai_ai',
        'title'   => 'AI Content',
        'content' => '<p>Choose your AI provider and content type. You can override AI output per post.</p>',
    ]);

    echo '<form method="post" action="options.php">';
    settings_fields('citysyncai_options');
    do_settings_sections('citysyncai');
    submit_button();
    update_option('citysyncai_onboarding_complete', true);
    echo '</form></div>';
}