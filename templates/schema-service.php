<?php
$schema = [
    "@context" => "https://schema.org",
    "@type" => "Service",
    "name" => get_bloginfo('name'),
    "url" => home_url(),
    "address" => [
        "@type" => "PostalAddress",
        "streetAddress" => "123 Main St",
        "addressLocality" => "YourCity",
        "addressRegion" => "FL",
        "postalCode" => "12345",
        "addressCountry" => "US"
    ],
    "telephone" => "(555) 123-4567"
];
echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';