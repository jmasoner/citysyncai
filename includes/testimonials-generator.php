<?php
/**
 * Testimonials Generator for CitySyncAI
 * Generates realistic testimonials for social proof
 */

defined('ABSPATH') || exit;

/**
 * Get testimonials for a city
 *
 * @param string $city_full
 * @return array Array of testimonials
 */
function citysyncai_get_testimonials($city_full) {
    // Get cached or generate
    $cache_key = 'citysyncai_testimonials_' . md5($city_full);
    $testimonials = get_transient($cache_key);
    
    if (!$testimonials) {
        $testimonials = citysyncai_generate_testimonials($city_full);
        set_transient($cache_key, $testimonials, 30 * DAY_IN_SECONDS);
    }
    
    return $testimonials ?: citysyncai_get_default_testimonials();
}

/**
 * Generate testimonials using AI
 *
 * @param string $city_full
 * @return array
 */
function citysyncai_generate_testimonials($city_full) {
    $provider = get_option('citysyncai_ai_provider', 'gemini');
    
    $prompt = "Generate 3-4 realistic business testimonials for a telecom consulting company helping businesses in {$city_full} find fiber internet and telecom services.

Testimonials should:
- Mention cost savings, time savings, or ease of process
- Be from business owners or IT managers
- Include company names (realistic but fictional)
- Be 1-2 sentences each
- Sound authentic and professional

Format as JSON array with 'text', 'author', and 'company' keys.";

    $response = citysyncai_dispatch_ai_provider($provider, 'testimonial', $city_full, $prompt);
    $testimonials = json_decode($response, true);
    
    return is_array($testimonials) ? $testimonials : [];
}

/**
 * Default testimonials
 *
 * @return array
 */
function citysyncai_get_default_testimonials() {
    return [
        [
            'text' => 'Saved us thousands per year and found fiber options we didn\'t know existed. The process was seamless.',
            'author' => 'Sarah Martinez',
            'company' => 'Martinez Manufacturing'
        ],
        [
            'text' => 'They handled everything - compared all carriers, got us the best pricing, and managed the installation. Highly recommend.',
            'author' => 'James Wilson',
            'company' => 'Wilson Logistics'
        ],
        [
            'text' => 'Tripled our bandwidth while cutting costs. Best telecom decision we\'ve made.',
            'author' => 'Jennifer Chen',
            'company' => 'Chen Technologies'
        ],
        [
            'text' => 'No need to call multiple providers. They did all the research and found us perfect solutions for all our locations.',
            'author' => 'Michael Rodriguez',
            'company' => 'Rodriguez Retail Group'
        ],
    ];
}

