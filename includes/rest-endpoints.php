<?php
/**
 * REST Endpoints for CitySyncAI
 */

defined('ABSPATH') || exit;

// 🔹 Generate AI content
function citysyncai_rest_generate_content($request) {
    $params       = $request->get_json_params();
    $provider     = sanitize_text_field($params['provider'] ?? get_option('citysyncai_ai_provider', 'openai'));
    $content_type = sanitize_text_field($params['type'] ?? get_option('citysyncai_content_type', 'overview'));
    $city         = sanitize_text_field($params['city'] ?? '');

    $prompt = $city ? "Generate SEO content for {$city} ({$content_type})" : "Generate SEO content for {$content_type}";
    $cache_key = 'citysyncai_' . md5($provider . '_' . $content_type . '_' . $city);
    $cached = get_transient($cache_key);

    if ($cached) {
        return [
            'provider'     => $provider,
            'content_type' => $content_type,
            'city'         => $city,
            'cached'       => true,
            'content'      => $cached,
        ];
    }

    ob_start();
    citysyncai_generate_ai_content($provider, $content_type);
    $output = ob_get_clean();
    set_transient($cache_key, $output, 12 * HOUR_IN_SECONDS);

    return [
        'provider'     => $provider,
        'content_type' => $content_type,
        'city'         => $city,
        'cached'       => false,
        'content'      => $output,
    ];
}

// 🔹 Export schema
function citysyncai_rest_schema_export($request) {
    $post_id = $request->get_param('post_id') ?? 0;
    $override = get_post_meta($post_id, '_citysyncai_custom_schema', true);

    if ($override) {
        return [
            'schema_type' => 'custom',
            'markup'      => $override,
        ];
    }

    $schema_type = get_option('citysyncai_schema_type', 'LocalBusiness');
    $template_path = plugin_dir_path(__DIR__) . '../templates/schema-' . strtolower($schema_type) . '.php';

    ob_start();
    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo "<!-- Schema template not found: $schema_type -->";
    }
    $output = ob_get_clean();

    return [
        'schema_type' => $schema_type,
        'markup'      => $output,
    ];
}
register_rest_route('citysyncai/v1', '/export-schema', [
    'methods'  => 'GET',
    'callback' => 'citysyncai_rest_bulk_schema_export',
    'permission_callback' => '__return_true',
]);

function citysyncai_rest_bulk_schema_export() {
    $posts = get_posts(['post_type' => ['post', 'page'], 'numberposts' => -1]);
    $output = [];

    foreach ($posts as $post) {
        $override = get_post_meta($post->ID, '_citysyncai_custom_schema', true);
        $type = get_post_meta($post->ID, '_citysyncai_schema_type', true) ?: get_option('citysyncai_schema_type', 'LocalBusiness');
        $template_path = plugin_dir_path(__DIR__) . "../templates/schema-" . strtolower($type) . ".php";

        ob_start();
        if ($override) {
            echo $override;
        } elseif (file_exists($template_path)) {
            include $template_path;
        }
        $markup = ob_get_clean();

        $output[] = [
            'post_id' => $post->ID,
            'title'   => $post->post_title,
            'schema_type' => $type,
            'markup'  => $markup,
        ];
    }

    return $output;
}

// 🔹 Preview cached AI content