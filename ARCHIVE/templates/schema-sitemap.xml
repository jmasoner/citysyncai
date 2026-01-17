<?php
/**
 * Schema Sitemap Generator for CitySyncAI
 * Outputs schema endpoints in XML format for external indexing.
 */

add_action('init', function () {
    add_rewrite_rule('^schema-sitemap\.xml$', 'index.php?citysyncai_schema_sitemap=1', 'top');
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'citysyncai_schema_sitemap';
    return $vars;
});

add_action('template_redirect', function () {
    if (get_query_var('citysyncai_schema_sitemap')) {
        header('Content-Type: application/xml; charset=utf-8');
        echo citysyncai_render_schema_sitemap();
        exit;
    }
});

function citysyncai_render_schema_sitemap() {
    $posts = get_posts([
        'post_type' => 'citysyncai_page',
        'post_status' => 'publish',
        'numberposts' => -1,
    ]);

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($posts as $post) {
        $schema_url = rest_url("citysyncai/v1/schema?id={$post->ID}");
        $lastmod = get_post_modified_time('c', true, $post);

        $xml .= "  <url>\n";
        $xml .= "    <loc>{$schema_url}</loc>\n";
        $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
        $xml .= "  </url>\n";
    }

    $xml .= '</urlset>';
    return $xml;
}