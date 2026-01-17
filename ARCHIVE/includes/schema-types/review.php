<?php
return [
    "@context" => "https://schema.org",
    "@type" => "Review",
    "itemReviewed" => [
        "@type" => "LocalBusiness",
        "name" => esc_html(get_the_title()),
    ],
    "reviewBody" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_review_body', true)),
    "reviewRating" => [
        "@type" => "Rating",
        "ratingValue" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_rating', true)),
        "bestRating" => "5",
    ],
    "author" => [
        "@type" => "Person",
        "name" => esc_html(get_post_meta(get_the_ID(), 'citysyncai_reviewer', true)),
    ],
    "url" => esc_url(get_permalink()),
];