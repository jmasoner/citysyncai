<?php
/**
 * City Pages Management for CitySyncAI
 * Handles hybrid dynamic/static page generation
 * B2B focused - no residential services
 */

defined('ABSPATH') || exit;

// Register Custom Post Type for City Pages
add_action('init', 'citysyncai_register_city_post_type');

// Add template loader
add_filter('single_template', 'citysyncai_load_city_template');

function citysyncai_load_city_template($template) {
    global $post;
    
    if ($post->post_type === 'citysyncai_city') {
        $plugin_template = CITYSYNCAI_DIR . 'templates/single-citysyncai_city.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    
    return $template;
}

function citysyncai_register_city_post_type() {
    $labels = [
        'name' => 'City Pages',
        'singular_name' => 'City Page',
        'menu_name' => 'City Pages',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New City Page',
        'edit_item' => 'Edit City Page',
        'new_item' => 'New City Page',
        'view_item' => 'View City Page',
        'search_items' => 'Search City Pages',
        'not_found' => 'No city pages found',
        'not_found_in_trash' => 'No city pages found in trash',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'city', 'with_front' => false],
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-location',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ];

    register_post_type('citysyncai_city', $args);
}

// Add rewrite rules for city pages
add_action('init', 'citysyncai_add_city_rewrite_rules');

function citysyncai_add_city_rewrite_rules() {
    add_rewrite_rule(
        '^city/([^/]+)/?$',
        'index.php?post_type=citysyncai_city&city_slug=$matches[1]',
        'top'
    );
}

// Add query var for city slug
add_filter('query_vars', 'citysyncai_add_city_query_var');

function citysyncai_add_city_query_var($vars) {
    $vars[] = 'city_slug';
    return $vars;
}

// Handle city page requests - auto-create if doesn't exist (hybrid approach)
add_action('template_redirect', 'citysyncai_handle_city_page_request', 1);

function citysyncai_handle_city_page_request() {
    // Only handle if it's a city page request
    global $wp_query;
    
    if (is_admin() || !isset($wp_query->query_vars['city_slug'])) {
        return;
    }

    $city_slug = $wp_query->query_vars['city_slug'];
    if (empty($city_slug)) {
        return;
    }

    // Try to find existing post by slug
    $existing_post = get_page_by_path($city_slug, OBJECT, 'citysyncai_city');
    
    if ($existing_post) {
        // Post exists, set up the query properly
        $wp_query->queried_object = $existing_post;
        $wp_query->queried_object_id = $existing_post->ID;
        $wp_query->is_singular = true;
        $wp_query->is_404 = false;
        return;
    }

    // Post doesn't exist - create it dynamically (hybrid approach)
    $city_data = citysyncai_parse_city_slug($city_slug);
    
    if (!$city_data) {
        // Invalid city slug, return 404
        $wp_query->is_404 = true;
        return;
    }

    // Generate content and create post
    $post_id = citysyncai_create_city_page($city_data['city'], $city_data['state']);
    
    if (!is_wp_error($post_id) && $post_id) {
        // Redirect to the new post
        $post = get_post($post_id);
        if ($post) {
            wp_redirect(get_permalink($post_id), 301);
            exit;
        }
    }
}

/**
 * Parse city slug to extract city and state
 * Format: city-state or city-state-abbreviation
 * Examples: austin-tx, new-york-ny
 *
 * @param string $slug
 * @return array|false
 */
function citysyncai_parse_city_slug($slug) {
    // Remove trailing slash if present
    $slug = trim($slug, '/');
    
    // Split by last dash (state is typically last segment)
    $parts = explode('-', $slug);
    if (count($parts) < 2) {
        return false;
    }

    // Last part should be state abbreviation (2 chars)
    $state = strtoupper(array_pop($parts));
    if (strlen($state) !== 2) {
        return false;
    }

    // Remaining parts are city name
    $city = implode(' ', $parts);
    $city = ucwords(str_replace('-', ' ', $city));

    return [
        'city' => $city,
        'state' => $state,
        'slug' => $slug,
    ];
}

/**
 * Create a city page post
 *
 * @param string $city City name
 * @param string $state State abbreviation
 * @return int|WP_Error Post ID or error
 */
function citysyncai_create_city_page($city, $state) {
    $city_full = "{$city}, {$state}";
    $slug = citysyncai_generate_city_slug($city, $state);

    // Check if already exists
    $existing = get_page_by_path($slug, OBJECT, 'citysyncai_city');
    if ($existing) {
        return $existing->ID;
    }

    // Generate AI content (B2B focused)
    $provider = get_option('citysyncai_ai_provider', 'gemini');
    $content_type = get_option('citysyncai_content_type', 'overview');
    
    // Generate content directly (don't use the preview wrapper function)
    $ai_content = citysyncai_dispatch_ai_provider($provider, $content_type, $city_full);
    
    // Clean up any error messages that might be wrapped in HTML
    if (strpos($ai_content, 'citysyncai-error') !== false) {
        // If there's an error, log it and use a fallback
        citysyncai_log_error("AI content generation failed for {$city_full}: {$ai_content}");
        $ai_content = "<p>We're currently updating content for {$city_full}. Please check back soon or contact us for more information.</p>";
    }
    
    // Trim whitespace
    $ai_content = trim($ai_content);

    // Create post
    $post_data = [
        'post_title' => "Business Telecom Services in {$city_full}",
        'post_content' => $ai_content,
        'post_status' => 'publish',
        'post_type' => 'citysyncai_city',
        'post_name' => $slug,
        'post_excerpt' => "Professional business telecom and communication services in {$city_full}. Enterprise solutions for companies with $1,500-$150,000 monthly telecom spend.",
    ];

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        citysyncai_log_error("Failed to create city page for {$city_full}: " . $post_id->get_error_message());
        return $post_id;
    }

    // Store city metadata
    update_post_meta($post_id, '_citysyncai_city', $city);
    update_post_meta($post_id, '_citysyncai_state', $state);
    update_post_meta($post_id, '_citysyncai_city_full', $city_full);
    update_post_meta($post_id, '_citysyncai_generated', current_time('mysql'));
    update_post_meta($post_id, '_citysyncai_provider', $provider);
    update_post_meta($post_id, '_citysyncai_content_type', $content_type);

    // Get city data if available
    $city_info = citysyncai_get_city_data($city, $state);
    if ($city_info) {
        update_post_meta($post_id, '_citysyncai_population', $city_info['population'] ?? '');
        update_post_meta($post_id, '_citysyncai_latitude', $city_info['latitude'] ?? '');
        update_post_meta($post_id, '_citysyncai_longitude', $city_info['longitude'] ?? '');
        update_post_meta($post_id, '_citysyncai_tier', $city_info['tier'] ?? '');
    }

    // Inject schema
    $schema_enabled = get_option('citysyncai_enable_schema', true);
    if ($schema_enabled) {
        $schema = citysyncai_get_schema_for_city($city_full, $post_id);
        update_post_meta($post_id, '_citysyncai_schema', $schema);
    }

    // Flush rewrite rules if needed
    flush_rewrite_rules(false);

    return $post_id;
}

/**
 * Generate URL-friendly slug from city and state
 *
 * @param string $city
 * @param string $state
 * @return string
 */
if (!function_exists('citysyncai_generate_city_slug')) {
    function citysyncai_generate_city_slug($city, $state) {
        $city_clean = strtolower($city);
        $city_clean = preg_replace('/[^a-z0-9\s-]/', '', $city_clean);
        $city_clean = preg_replace('/[\s-]+/', '-', $city_clean);
        $city_clean = trim($city_clean, '-');
        $state_clean = strtolower($state);
        
        return "{$city_clean}-{$state_clean}";
    }
}

/**
 * Get schema markup for city page
 *
 * @param string $city_full
 * @param int $post_id
 * @return string
 */
function citysyncai_get_schema_for_city($city_full, $post_id) {
    $schema_type = get_option('citysyncai_schema_type', 'LocalBusiness');
    $template = CITYSYNCAI_DIR . 'templates/schema-' . strtolower($schema_type) . '.php';
    
    if (!file_exists($template)) {
        return '';
    }

    // Make city data available to template
    $GLOBALS['citysyncai_city_data'] = [
        'city_full' => $city_full,
        'post_id' => $post_id,
    ];

    ob_start();
    include $template;
    return ob_get_clean();
}

// Add city slug to permalink
add_filter('post_type_link', 'citysyncai_city_permalink', 10, 2);

function citysyncai_city_permalink($post_link, $post) {
    if ($post->post_type !== 'citysyncai_city') {
        return $post_link;
    }

    $city = get_post_meta($post->ID, '_citysyncai_city', true);
    $state = get_post_meta($post->ID, '_citysyncai_state', true);

    if ($city && $state) {
        $slug = citysyncai_generate_city_slug($city, $state);
        return home_url("/city/{$slug}/");
    }

    return $post_link;
}

