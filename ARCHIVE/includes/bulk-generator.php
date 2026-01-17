<?php
/**
 * Bulk City Page Generator for CitySyncAI
 * Pre-generates top 500 tier 2/3 cities
 * B2B focused - no residential services
 */

defined('ABSPATH') || exit;

// Add admin menu for bulk generator
add_action('admin_menu', 'citysyncai_add_bulk_generator_menu');

function citysyncai_add_bulk_generator_menu() {
    add_submenu_page(
        'edit.php?post_type=citysyncai_city',
        'Bulk Generator',
        'Bulk Generator',
        'manage_options',
        'citysyncai-bulk-generator',
        'citysyncai_render_bulk_generator_page'
    );
}

/**
 * Render bulk generator admin page
 */
function citysyncai_render_bulk_generator_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to access this page.');
    }

    // Handle form submission
    if (isset($_POST['citysyncai_bulk_generate']) && check_admin_referer('citysyncai_bulk_generate')) {
        $count = isset($_POST['city_count']) ? intval($_POST['city_count']) : 500;
        $tiers = isset($_POST['city_tiers']) ? array_map('intval', $_POST['city_tiers']) : [2, 3];
        
        // Start background generation process
        update_option('citysyncai_bulk_generation_status', [
            'status' => 'running',
            'total' => $count,
            'completed' => 0,
            'failed' => 0,
            'started_at' => current_time('mysql'),
            'tiers' => $tiers,
        ]);
        
        // Process first batch immediately
        citysyncai_process_bulk_generation_batch($count, $tiers);
    }

    // Get generation status
    $status = get_option('citysyncai_bulk_generation_status', null);
    $top_cities = citysyncai_get_top_tier2_tier3_cities(500);
    
    ?>
    <div class="wrap">
        <h1>CitySyncAI Bulk Generator</h1>
        <p class="description">Pre-generate city pages for tier 2 and tier 3 cities (B2B focused - no residential services).</p>
        
        <?php if ($status && $status['status'] === 'running'): ?>
            <div class="notice notice-info">
                <p><strong>Generation in progress...</strong></p>
                <p>Completed: <?php echo esc_html($status['completed']); ?> / <?php echo esc_html($status['total']); ?></p>
                <p>Failed: <?php echo esc_html($status['failed']); ?></p>
                <?php if ($status['completed'] < $status['total']): ?>
                    <p><a href="<?php echo esc_url(admin_url('admin.php?page=citysyncai-bulk-generator&continue=1')); ?>" class="button">Continue Generation</a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="card" style="max-width: 800px;">
            <h2>Generate Top 500 Tier 2/3 Cities</h2>
            <p>This will pre-generate WordPress posts for the top tier 2 and tier 3 cities (excluding major metros like NYC, LA, Chicago).</p>
            <p><strong>Target:</strong> B2B customers spending $1,500-$150,000/month on telecom services (NO residential).</p>
            
            <form method="post">
                <?php wp_nonce_field('citysyncai_bulk_generate'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">Number of Cities</th>
                        <td>
                            <input type="number" name="city_count" value="500" min="1" max="5000" class="small-text" />
                            <p class="description">Recommended: 500 cities. Available tier 2/3 cities: <?php echo esc_html(count($top_cities)); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">City Tiers</th>
                        <td>
                            <label><input type="checkbox" name="city_tiers[]" value="2" checked /> Tier 2 (500K+ population - Secondary metros)</label><br>
                            <label><input type="checkbox" name="city_tiers[]" value="3" checked /> Tier 3 (100K-500K population - Smaller metros)</label>
                            <p class="description">Tier 1 cities (major metros) are automatically excluded.</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="citysyncai_bulk_generate" class="button button-primary" value="Start Bulk Generation" />
                </p>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Preview: Top 50 Tier 2/3 Cities</h2>
            <p>These are the cities that will be generated first:</p>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>City</th>
                        <th>State</th>
                        <th>Population</th>
                        <th>Tier</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($top_cities, 0, 50) as $city): 
                        $city_full = "{$city['city']}, {$city['state']}";
                        $slug = citysyncai_generate_city_slug($city['city'], $city['state']);
                        $existing = get_page_by_path($slug, OBJECT, 'citysyncai_city');
                    ?>
                        <tr>
                            <td><?php echo esc_html($city['city']); ?></td>
                            <td><?php echo esc_html($city['state']); ?></td>
                            <td><?php echo esc_html(number_format($city['population'] ?? 0)); ?></td>
                            <td>Tier <?php echo esc_html($city['tier'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if ($existing): ?>
                                    <span style="color: green;">✓ Generated</span>
                                <?php else: ?>
                                    <span style="color: gray;">Not generated</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <style>
        .citysyncai-progress {
            width: 100%;
            height: 30px;
            background: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
            margin: 10px 0;
        }
        .citysyncai-progress-bar {
            height: 100%;
            background: #2271b1;
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
    <?php
}

/**
 * Process a batch of city page generation
 *
 * @param int $total_count Total number of cities to generate
 * @param array $tiers Array of tier numbers to include
 * @param int $batch_size Number of cities to process per batch
 */
function citysyncai_process_bulk_generation_batch($total_count = 500, $tiers = [2, 3], $batch_size = 10) {
    $status = get_option('citysyncai_bulk_generation_status', [
        'status' => 'running',
        'total' => $total_count,
        'completed' => 0,
        'failed' => 0,
        'tiers' => $tiers,
    ]);

    if ($status['status'] !== 'running') {
        return;
    }

    $cities = citysyncai_get_cities_by_tier($tiers, $total_count);
    $completed = $status['completed'];
    $failed = $status['failed'];
    
    // Process batch
    $batch = array_slice($cities, $completed, $batch_size);
    
    foreach ($batch as $city_data) {
        $city_full = "{$city_data['city']}, {$city_data['state']}";
        $slug = citysyncai_generate_city_slug($city_data['city'], $city_data['state']);
        
        // Skip if already exists
        $existing = get_page_by_path($slug, OBJECT, 'citysyncai_city');
        if ($existing) {
            $completed++;
            continue;
        }
        
        // Create city page
        $result = citysyncai_create_city_page($city_data['city'], $city_data['state']);
        
        if (is_wp_error($result)) {
            $failed++;
            citysyncai_log_error("Failed to generate page for {$city_full}: " . $result->get_error_message());
        } else {
            $completed++;
        }
        
        // Respect rate limits - small delay between requests
        usleep(200000); // 0.2 second delay (60 req/min = 1 req/sec, so this is safe)
    }
    
    // Update status
    $status['completed'] = $completed;
    $status['failed'] = $failed;
    
    if ($completed >= $status['total']) {
        $status['status'] = 'completed';
        $status['completed_at'] = current_time('mysql');
    }
    
    update_option('citysyncai_bulk_generation_status', $status);
    
    // If not done, schedule next batch
    if ($completed < $status['total']) {
        // Use WordPress cron for next batch
        wp_schedule_single_event(time() + 10, 'citysyncai_process_next_batch');
    }
}

// Hook for processing next batch
add_action('citysyncai_process_next_batch', function() {
    $status = get_option('citysyncai_bulk_generation_status', null);
    if ($status && $status['status'] === 'running') {
        citysyncai_process_bulk_generation_batch(
            $status['total'],
            $status['tiers'] ?? [2, 3],
            10
        );
    }
});

// AJAX endpoint for status updates
add_action('wp_ajax_citysyncai_bulk_status', 'citysyncai_ajax_bulk_status');

function citysyncai_ajax_bulk_status() {
    check_ajax_referer('citysyncai_bulk_status');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }
    
    $status = get_option('citysyncai_bulk_generation_status', null);
    wp_send_json_success($status);
}


