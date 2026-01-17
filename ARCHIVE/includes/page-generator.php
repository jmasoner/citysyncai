<?php
function citysyncai_shortcode_handler($atts) {
    $atts = shortcode_atts([
        'city' => 'New York, NY',
        'type' => 'overview',
    ], $atts);

    $content = citysyncai_generate_content($atts['city'], $atts['type']);
    return $content;
}

function citysyncai_rest_generate($request) {
    $params = $request->get_json_params();
    $city = sanitize_text_field($params['city'] ?? '');
    $type = sanitize_text_field($params['type'] ?? 'overview');

    $content = citysyncai_generate_content($city, $type);
    return rest_ensure_response(['content' => $content]);
}