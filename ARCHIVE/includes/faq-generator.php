<?php
/**
 * FAQ Generator for CitySyncAI
 * AI-generated FAQs optimized for SEO and Gemini search
 * Uses Telarus.com data for factual accuracy
 */

defined('ABSPATH') || exit;

/**
 * Render FAQ section with schema markup
 *
 * @param string $city_full City and state
 * @param int $post_id Post ID
 */
function citysyncai_render_faq_section($city_full, $post_id) {
    // Get cached FAQs or generate new ones
    $cache_key = 'citysyncai_faq_' . md5($city_full);
    $faqs = get_transient($cache_key);
    
    if (!$faqs) {
        $faqs = citysyncai_generate_faqs($city_full);
        set_transient($cache_key, $faqs, 30 * DAY_IN_SECONDS);
    }
    
    if (empty($faqs)) {
        return;
    }
    
    // Render FAQ list
    echo '<div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">';
    
    foreach ($faqs as $index => $faq) {
        echo '<div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">';
        echo '<div class="faq-question" onclick="citysyncaiToggleFAQ(' . $index . ')">';
        echo '<span itemprop="name">' . esc_html($faq['question']) . '</span>';
        echo '<span class="faq-toggle">+</span>';
        echo '</div>';
        echo '<div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">';
        echo '<div itemprop="text">' . wp_kses_post($faq['answer']) . '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    
    // Add JavaScript for FAQ toggle
    ?>
    <script>
    function citysyncaiToggleFAQ(index) {
        const item = document.querySelectorAll('.faq-item')[index];
        const toggle = item.querySelector('.faq-toggle');
        item.classList.toggle('active');
        toggle.textContent = item.classList.contains('active') ? '−' : '+';
    }
    </script>
    <?php
}

/**
 * Generate FAQs using AI
 *
 * @param string $city_full
 * @return array Array of FAQ items
 */
function citysyncai_generate_faqs($city_full) {
    $provider = get_option('citysyncai_ai_provider', 'gemini');
    
    // Build prompt with Telarus context
    $prompt = "Generate 8-10 frequently asked questions about business fiber internet and telecom services in {$city_full}. 

Focus on:
- Fiber availability and pricing
- Business internet options
- Telecom services for businesses spending $1,500-$150,000/month
- Installation timelines
- Service comparisons
- Cost savings
- Provider selection

Make questions natural and SEO-friendly. Include factual information about business telecom services. 

Format as JSON array with 'question' and 'answer' keys. Answers should be 2-3 sentences, informative, and include local context when relevant.

Example format:
[
  {
    'question': 'What fiber internet options are available for businesses in [City]?',
    'answer': 'Businesses in [City] have access to multiple fiber internet providers including [specific options]. We can check availability at your specific address and compare pricing from all carriers.'
  }
]";

    // Call AI to generate FAQs
    $response = citysyncai_dispatch_ai_provider($provider, 'faq', $city_full, $prompt);
    
    // Parse JSON response
    $faqs = json_decode($response, true);
    
    if (!is_array($faqs)) {
        // Fallback to default FAQs
        return citysyncai_get_default_faqs($city_full);
    }
    
    return $faqs;
}

/**
 * Get default FAQs if AI generation fails
 *
 * @param string $city_full
 * @return array
 */
function citysyncai_get_default_faqs($city_full) {
    return [
        [
            'question' => "What fiber internet options are available for businesses in {$city_full}?",
            'answer' => "Businesses in {$city_full} have access to multiple fiber internet providers. We can check availability at your specific address and compare pricing from all carriers, giving you the best options available in your area."
        ],
        [
            'question' => "How do I check fiber availability at my business address?",
            'answer' => "Simply enter your business address in our availability checker above. We'll provide same-day availability information from all major carriers at no cost. No obligation required."
        ],
        [
            'question' => "How much does business fiber internet cost in {$city_full}?",
            'answer' => "Business fiber pricing varies based on speed, location, and carrier. For businesses spending $1,500-$150,000 per month, we can help you find competitive rates. Contact us for a free quote tailored to your specific needs."
        ],
        [
            'question' => "What's the difference between business fiber and regular internet?",
            'answer' => "Business fiber offers symmetrical speeds (same upload and download), higher reliability with SLA guarantees, dedicated bandwidth, and priority support. It's designed for businesses that need consistent, high-speed connectivity."
        ],
        [
            'question' => "How long does it take to get fiber internet installed?",
            'answer' => "Installation timelines vary by location and carrier. If fiber is already available at your address, installation can typically be completed within 2-4 weeks. We can provide specific timelines when you check availability."
        ],
        [
            'question' => "Do you work with multiple carriers?",
            'answer' => "Yes, we partner with 50+ carriers including all major business internet providers. This allows us to compare options and find you the best pricing and service for your business needs."
        ],
        [
            'question' => "What other telecom services do you offer?",
            'answer' => "Beyond fiber internet, we provide business phone systems, VoIP solutions, cloud services, unified communications, network security, and more. We're a single-source provider for all your business telecom needs."
        ],
        [
            'question' => "Is there a cost to check availability?",
            'answer' => "No, checking fiber availability is completely free with no obligation. We provide same-day availability information from all carriers at no cost to you."
        ],
    ];
}


