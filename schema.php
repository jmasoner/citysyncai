<?php
/**
 * Injects SEO schema into the <head> for CitySyncAI pages.
 */

function citysyncai_inject_schema() {
    if (is_singular('citysyncai_page')) {
        $city = get_post_meta(get_the_ID(), 'citysyncai_city', true);
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
            "name" => esc_html(get_the_title()),
            "url" => esc_url(get_permalink()),
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => esc_html($city),
            ],
        ];

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'citysyncai_inject_schema');
$cache_key = 'citysyncai_schema_' . $post_id;
$cached = get_transient($cache_key);
if ($cached) return rest_ensure_response(json_decode($cached, true));

$schema = include $schema_file;
set_transient($cache_key, wp_json_encode($schema), HOUR_IN_SECONDS);