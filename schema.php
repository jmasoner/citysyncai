add_action('wp_head', function () {
    if (is_singular('citysyncai_page')) {
        echo '<script type="application/ld+json">' . json_encode([
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
            "name" => get_the_title(),
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => get_post_meta(get_the_ID(), 'citysyncai_city', true),
            ],
        ]) . '</script>';
    }
});