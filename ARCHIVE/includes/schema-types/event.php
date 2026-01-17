<?php
return [
    "@context" => "https://schema.org",
    "@type" => "Event",
    "name" => esc_html(get_the_title()),
    "startDate" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_start_date', true)), // ISO 8601 format
    "location" => [
        "@type" => "Place",
        "name" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_venue', true)),
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_city', true)),
        ],
    ],
    "url" => esc_url(get_permalink()),
];