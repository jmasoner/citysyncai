<?php
/**
 * Quick City Page Creator
 * Admin interface to create city pages
 */

defined('ABSPATH') || exit;

// Add admin menu
add_action('admin_menu', 'citysyncai_add_city_creator_menu');

function citysyncai_add_city_creator_menu() {
    add_submenu_page(
        'edit.php?post_type=citysyncai_city',
        'Create City Pages',
        'Create Cities',
        'manage_options',
        'citysyncai-create-cities',
        'citysyncai_render_city_creator_page'
    );
}

function citysyncai_render_city_creator_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    // Auto-create the 3 requested cities if they don't exist
    $auto_cities = [
        ['city' => 'Walla Walla', 'state' => 'WA'],
        ['city' => 'Andalusia', 'state' => 'AL'],
        ['city' => 'Destin', 'state' => 'FL'],
    ];
    
    $auto_created = [];
    foreach ($auto_cities as $city_data) {
        $slug = citysyncai_generate_city_slug($city_data['city'], $city_data['state']);
        $existing = get_page_by_path($slug, OBJECT, 'citysyncai_city');
        
        if (!$existing) {
            $result = citysyncai_create_city_page($city_data['city'], $city_data['state']);
            if (!is_wp_error($result)) {
                $auto_created[] = [
                    'city' => "{$city_data['city']}, {$city_data['state']}",
                    'post_id' => $result,
                    'url' => get_permalink($result)
                ];
            }
        }
    }
    
    // Handle form submission
    if (isset($_POST['create_cities']) && check_admin_referer('citysyncai_create_cities')) {
        $cities_input = sanitize_textarea_field($_POST['cities']);
        $cities = array_filter(array_map('trim', explode("\n", $cities_input)));
        
        $results = [];
        foreach ($cities as $city_line) {
            // Parse "City, State" format
            $parts = explode(',', $city_line);
            if (count($parts) === 2) {
                $city = trim($parts[0]);
                $state = trim($parts[1]);
                
                $result = citysyncai_create_city_page($city, $state);
                if (is_wp_error($result)) {
                    $results[] = [
                        'city' => "{$city}, {$state}",
                        'status' => 'error',
                        'message' => $result->get_error_message()
                    ];
                } else {
                    $results[] = [
                        'city' => "{$city}, {$state}",
                        'status' => 'success',
                        'post_id' => $result,
                        'url' => get_permalink($result)
                    ];
                }
            }
        }
    }
    
    ?>
    <div class="wrap">
        <h1>Create City Pages</h1>
        
        <?php if (!empty($auto_created)): ?>
            <div class="notice notice-success is-dismissible">
                <h2>Auto-Created Cities:</h2>
                <ul>
                    <?php foreach ($auto_created as $created): ?>
                        <li>
                            <strong><?php echo esc_html($created['city']); ?></strong> - 
                            <a href="<?php echo esc_url($created['url']); ?>" target="_blank">View Page</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (isset($results)): ?>
            <div class="notice notice-info">
                <h2>Results:</h2>
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li>
                            <strong><?php echo esc_html($result['city']); ?></strong>: 
                            <?php if ($result['status'] === 'success'): ?>
                                <span style="color: green;">✓ Created</span> - 
                                <a href="<?php echo esc_url($result['url']); ?>" target="_blank">View Page</a>
                            <?php else: ?>
                                <span style="color: red;">✗ Error: <?php echo esc_html($result['message']); ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="card" style="max-width: 800px;">
            <h2>Quick Create</h2>
            <p>Enter cities in format: <code>City Name, State</code> (one per line)</p>
            
            <form method="post">
                <?php wp_nonce_field('citysyncai_create_cities'); ?>
                
                <p>
                    <label for="cities">Cities to Create:</label><br>
                    <textarea name="cities" id="cities" rows="10" cols="50" class="large-text">Walla Walla, WA
Andalusia, AL
Destin, FL</textarea>
                </p>
                
                <p class="submit">
                    <input type="submit" name="create_cities" class="button button-primary" value="Create City Pages" />
                    <span class="description">This will generate AI content for each city and create WordPress posts.</span>
                </p>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Pre-defined Cities</h2>
            <p>Quick buttons to create specific cities:</p>
            <form method="post">
                <?php wp_nonce_field('citysyncai_create_cities'); ?>
                <input type="hidden" name="cities" value="Walla Walla, WA
Andalusia, AL
Destin, FL">
                <p class="submit">
                    <input type="submit" name="create_cities" class="button button-primary" value="Create: Walla Walla WA, Andalusia AL, Destin FL" />
                </p>
            </form>
        </div>
    </div>
    <?php
}

