<?php
return [
    "@context" => "https://schema.org",
    "@type" => "Service",
    "serviceType" => esc_html(get_the_title()),
    "provider" => [
        "@type" => "LocalBusiness",
        "name" => esc_html(get_the_title()),
        "url" => esc_url(get_permalink()),
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_city', true)),
        ],
    ],
];