<?php
function citysyncai_generate_content($city, $type) {
    $prompt = "Generate a {$type} page for {$city} with SEO-rich content.";
    // Replace with actual AI call
    $response = citysyncai_mock_ai_response($prompt);
    return $response;
}

function citysyncai_mock_ai_response($prompt) {
    return "<h2>Welcome to {$prompt}</h2><p>This is AI-generated content for SEO optimization.</p>";
}