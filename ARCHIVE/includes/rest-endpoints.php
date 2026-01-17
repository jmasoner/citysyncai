<?php
/**
 * REST Endpoints for CitySyncAI
 */

defined('ABSPATH') || exit;

// Register REST routes
add_action('rest_api_init', 'citysyncai_register_rest_routes');

function citysyncai_register_rest_routes() {
    // Generate AI content endpoint
    register_rest_route('citysyncai/v1', '/generate', [
        'methods' => 'POST',
        'callback' => 'citysyncai_rest_generate_content',
        'permission_callback' => function () {
            return current_user_can('manage_options'); // Only admins can generate content
        },
        'args' => [
            'provider' => [
                'type' => 'string',
                'default' => '',
            ],
            'type' => [
                'type' => 'string',
                'default' => 'overview',
            ],
            'city' => [
                'type' => 'string',
                'default' => '',
            ],
        ],
    ]);

    // Get schema endpoint
    register_rest_route('citysyncai/v1', '/schema', [
        'methods' => 'GET',
        'callback' => 'citysyncai_rest_get_schema',
        'permission_callback' => '__return_true',
    ]);

    // Validate schema endpoint
    register_rest_route('citysyncai/v1', '/validate-schema', [
        'methods' => 'GET',
        'callback' => 'citysyncai_rest_validate_schema',
        'permission_callback' => '__return_true',
    ]);

    // Trigger webhook endpoint
    register_rest_route('citysyncai/v1', '/trigger-webhook', [
        'methods' => 'POST',
        'callback' => 'citysyncai_rest_trigger_webhook',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ]);

    // Export settings endpoint
    register_rest_route('citysyncai/v1', '/export-settings', [
        'methods' => 'GET',
        'callback' => 'citysyncai_rest_export_settings',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ]);

    // Export markdown endpoint
    register_rest_route('citysyncai/v1', '/export-markdown', [
        'methods' => 'GET',
        'callback' => 'citysyncai_rest_export_markdown',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ]);
}

// 🔹 Generate AI content
function citysyncai_rest_generate_content($request) {
    $params = $request->get_json_params() ?: [];
    $provider = sanitize_text_field($params['provider'] ?? get_option('citysyncai_ai_provider', 'gemini'));
    $content_type = sanitize_text_field($params['type'] ?? get_option('citysyncai_content_type', 'overview'));
    $city = sanitize_text_field($params['city'] ?? '');

    $cache_key = 'citysyncai_' . md5($provider . '_' . $content_type . '_' . $city);
    $cached = get_transient($cache_key);

    if ($cached) {
        return rest_ensure_response([
            'success' => true,
            'provider' => $provider,
            'content_type' => $content_type,
            'city' => $city,
            'cached' => true,
            'content' => $cached,
        ]);
    }

    ob_start();
    citysyncai_generate_ai_content($provider, $content_type, $city);
    $output = ob_get_clean();
    // Extract just the content without the wrapper div
    $output = preg_replace('/<div[^>]*class=[\'"]citysyncai-ai-content[\'"][^>]*>.*?<\/div>/s', '', $output);
    $output = trim($output);
    set_transient($cache_key, $output, 30 * DAY_IN_SECONDS); // Extended cache to 30 days

    return rest_ensure_response([
        'success' => true,
        'provider' => $provider,
        'content_type' => $content_type,
        'city' => $city,
        'cached' => false,
        'content' => $output,
    ]);
}

// 🔹 Get schema
function citysyncai_rest_get_schema($request) {
    $schema_type = get_option('citysyncai_schema_type', 'LocalBusiness');
    $template = CITYSYNCAI_DIR . 'templates/schema-' . strtolower($schema_type) . '.php';
    
    if (file_exists($template)) {
        ob_start();
        include $template;
        $schema = ob_get_clean();
        return rest_ensure_response([
            'success' => true,
            'schema_type' => $schema_type,
            'schema' => $schema,
        ]);
    }

    return new WP_Error('schema_not_found', 'Schema template not found', ['status' => 404]);
}

// 🔹 Validate schema
function citysyncai_rest_validate_schema($request) {
    $schema_type = get_option('citysyncai_schema_type', 'LocalBusiness');
    $template = CITYSYNCAI_DIR . 'templates/schema-' . strtolower($schema_type) . '.php';
    
    $markup = '';
    if (file_exists($template)) {
        ob_start();
        include $template;
        $markup = ob_get_clean();
    }

    // Basic validation
    $valid_jsonld = false;
    $has_script_tag = false;

    if (preg_match('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/s', $markup, $matches)) {
        $has_script_tag = true;
        $json = json_decode($matches[1], true);
        $valid_jsonld = json_last_error() === JSON_ERROR_NONE && is_array($json);
    }

    return rest_ensure_response([
        'schema_type' => $schema_type,
        'valid_jsonld' => $valid_jsonld,
        'has_script_tag' => $has_script_tag,
        'markup' => $markup,
    ]);
}

// 🔹 Trigger webhook
function citysyncai_rest_trigger_webhook($request) {
    $webhook_url = get_option('citysyncai_webhook_url', '');
    
    if (empty($webhook_url)) {
        return new WP_Error('no_webhook', 'Webhook URL not configured', ['status' => 400]);
    }

    $params = $request->get_json_params() ?: [];
    $data = [
        'timestamp' => current_time('mysql'),
        'action' => $params['action'] ?? 'citysyncai_trigger',
        'data' => $params['data'] ?? [],
    ];

    $response = wp_remote_post($webhook_url, [
        'body' => wp_json_encode($data),
        'headers' => ['Content-Type' => 'application/json'],
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('webhook_failed', $response->get_error_message(), ['status' => 500]);
    }

    return rest_ensure_response([
        'success' => true,
        'webhook_url' => $webhook_url,
        'response_code' => wp_remote_retrieve_response_code($response),
    ]);
}

// 🔹 Export settings
function citysyncai_rest_export_settings($request) {
    $settings = [
        'content_type' => get_option('citysyncai_content_type'),
        'ai_provider' => get_option('citysyncai_ai_provider'),
        'enable_ai' => get_option('citysyncai_enable_ai'),
        'enable_schema' => get_option('citysyncai_enable_schema'),
        'schema_type' => get_option('citysyncai_schema_type'),
        'sync_frequency' => get_option('citysyncai_sync_frequency'),
        'webhook_url' => get_option('citysyncai_webhook_url'),
        // Don't export API keys for security
    ];

    return rest_ensure_response([
        'success' => true,
        'settings' => $settings,
        'exported_at' => current_time('mysql'),
    ]);
}

// 🔹 Export markdown
function citysyncai_rest_export_markdown($request) {
    $posts = get_posts([
        'post_type' => ['post', 'page'],
        'numberposts' => -1,
        'post_status' => 'publish',
    ]);
    
    $output = [];

    foreach ($posts as $post) {
        $schema = get_post_meta($post->ID, '_citysyncai_custom_schema', true);
        $ai = get_post_meta($post->ID, '_citysyncai_custom_ai', true);

        $md = "## {$post->post_title}\n\n";
        $md .= "**Post ID:** {$post->ID}\n\n";
        $md .= "**URL:** " . get_permalink($post->ID) . "\n\n";
        
        if ($ai) {
            $md .= "**AI Content:**\n\n{$ai}\n\n";
        }
        
        if ($schema) {
            $md .= "**Schema Markup:**\n\n```json\n{$schema}\n```\n\n";
        }

        $output[] = [
            'post_id' => $post->ID,
            'title' => $post->post_title,
            'markdown' => $md,
        ];
    }

    return rest_ensure_response([
        'success' => true,
        'posts' => $output,
        'total' => count($output),
    ]);
}
