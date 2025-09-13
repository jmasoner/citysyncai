<?php
return [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "name" => esc_html(get_the_title()),
    "url" => esc_url(get_permalink()),
    "telephone" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_phone', true)),
    "address" => [
        "@type" => "PostalAddress",
        "addressLocality" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_city', true)),
    ],
    "geo" => [
        "@type" => "GeoCoordinates",
        "latitude" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_lat', true)),
        "longitude" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_lng', true)),
    ],
    "openingHours" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_hours', true)),
];