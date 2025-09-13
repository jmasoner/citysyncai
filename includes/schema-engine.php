<?php
/**
 * Dispatcher for modular schema injection in CitySyncAI.
 */

function citysyncai_inject_schema() {
    if (!is_singular('citysyncai_page')) {
        return;
    }

    // Determine schema type from post meta or fallback to plugin setting
    $post_id = get_the_ID();
    $meta_type = get_post_meta($post_id, 'citysyncai_type', true);
    $default_type = get_option('citysyncai_schema_type', 'LocalBusiness');
    $schema_type = $meta_type ?: $default_type;

    // Build path to schema file
    $schema_file = plugin_dir_path(__FILE__) . 'schema-types/' . strtolower($schema_type) . '.php';

    // Load and inject schema
    if (file_exists($schema_file)) {
        $schema = include $schema_file;

        if (is_array($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
        }
    }
}
add_action('wp_head', 'citysyncai_inject_schema');

/**
 * Optional: REST endpoint for external schema export
 */
function citysyncai_rest_schema_export($request) {
    $post_id = $request->get_param('id');
    if (!$post_id || !get_post($post_id)) {
        return new WP_Error('invalid_post', 'Valid post ID required', ['status' => 400]);
    }

    $meta_type = get_post_meta($post_id, 'citysyncai_type', true);
    $default_type = get_option('citysyncai_schema_type', 'LocalBusiness');
    $schema_type = $meta_type ?: $default_type;

    $schema_file = plugin_dir_path(__FILE__) . 'schema-types/' . strtolower($schema_type) . '.php';

    if (file_exists($schema_file)) {
        setup_postdata(get_post($post_id)); // Ensure template functions work
        $schema = include $schema_file;
        wp_reset_postdata();

        return is_array($schema) ? rest_ensure_response($schema) : new WP_Error('invalid_schema', 'Schema not valid', ['status' => 500]);
    }

    return new WP_Error('missing_schema', 'Schema file not found', ['status' => 404]);
}
add_action('rest_api_init', function () {
    register_rest_route('citysyncai/v1', '/schema', [
        'methods' => 'GET',
        'callback' => 'citysyncai_rest_schema_export',
        'permission_callback' => '__return_true',
    ]);
});
function citysyncai_generate_schema_from_ai($request) {
    $post_id = $request->get_param('id');
    $post = get_post($post_id);
    if (!$post) return new WP_Error('invalid_post', 'Post not found', ['status' => 404]);

    $content = $post->post_content;
    $title = get_the_title($post_id);
    $city = get_post_meta($post_id, 'citysyncai_city', true);

    $prompt = "Generate a schema.org JSON-LD block for a local business named '{$title}' located in '{$city}'. Use the following content for context:\n\n{$content}";

    // Replace this with your actual AI provider logic
    $schema_json = citysyncai_call_ai_provider($prompt);

    if (!$schema_json) return new WP_Error('ai_failed', 'AI did not return schema', ['status' => 500]);

    update_post_meta($post_id, '_citysyncai_generated_schema', $schema_json);

    return rest_ensure_response(json_decode($schema_json, true));
}
function citysyncai_call_ai_provider($prompt) {
    $provider = get_option('citysyncai_api_provider', 'gemini');
    $api_key = get_option('citysyncai_api_key', '');

    // Stub: Replace with actual Gemini/OpenAI/Claude call
    return json_encode([
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => "Stub Business",
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => "Stub City"
        ]
    ]);
}