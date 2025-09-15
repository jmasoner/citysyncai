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
register_rest_route('citysyncai/v1', '/export-markdown', [
    'methods'  => 'GET',
    'callback' => function () {
        $posts = get_posts(['post_type' => ['post', 'page'], 'numberposts' => -1]);
        $output = [];

        foreach ($posts as $post) {
            $schema = get_post_meta($post->ID, '_citysyncai_custom_schema', true);
            $ai     = get_post_meta($post->ID, '_citysyncai_custom_ai', true);

            $md = "## {$post->post_title}\n\n";
            if ($ai) {
                $md .= "**AI Content:**\n\n{$ai}\n\n";
            }
            if ($schema) {
                $md .= "**Schema Markup:**\n\n```json\n{$schema}\n```\n\n";
            }

            $output[] = [
                'post_id' => $post->ID,
                'markdown' => $md,
            ];
        }

        return $output;
    },
    'permission_callback' => '__return_true',
]);

// 🔹 Export schema
function citysyncai_rest_schema_export($