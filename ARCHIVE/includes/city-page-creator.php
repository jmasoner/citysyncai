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
    
    // NOTE: Auto-creation disabled - pages will only be created when you click the button
    // This prevents accidental creation and gives you control over when pages are generated
    // We're creating ONLY 3 test cities: Walla Walla WA, Victorville CA, Destin FL
    $auto_created = [];
    
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
            <h2>🚀 Create Test Cities (3 Cities for Testing)</h2>
            <p><strong>Purpose:</strong> Testing design, layout, and content quality before bulk generation.</p>
            <p>Enter cities in format: <code>City Name, State</code> (one per line)</p>
            
            <form method="post">
                <?php wp_nonce_field('citysyncai_create_cities'); ?>
                
                <p>
                    <label for="cities">Cities to Create:</label><br>
                    <textarea name="cities" id="cities" rows="10" cols="50" class="large-text">Walla Walla, WA
Victorville, CA
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
Victorville, CA
Destin, FL">
                <p class="submit">
                    <input type="submit" name="create_cities" class="button button-primary" value="Create Test Cities: Walla Walla WA, Victorville CA, Destin FL" />
                </p>
            </form>
        </div>
    </div>
    <?php
}

