<?php
/**
 * Address Checker / Telarus GeoQuote Integration
 * Handles fiber availability checking
 */

defined('ABSPATH') || exit;

/**
 * Handle address checker form submission
 * This will integrate with Telarus GeoQuote API
 */
add_action('wp_ajax_citysyncai_check_address', 'citysyncai_handle_address_check');
add_action('wp_ajax_nopriv_citysyncai_check_address', 'citysyncai_handle_address_check');

function citysyncai_handle_address_check() {
    check_ajax_referer('citysyncai_address_check', 'nonce');
    
    $address = sanitize_text_field($_POST['address'] ?? '');
    $business_name = sanitize_text_field($_POST['business_name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    
    if (empty($address)) {
        wp_send_json_error(['message' => 'Address is required']);
    }
    
    // TODO: Integrate with Telarus GeoQuote API
    // For now, store lead and return placeholder response
    
    // Save lead
    citysyncai_save_lead([
        'type' => 'address_check',
        'business_name' => $business_name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'timestamp' => current_time('mysql'),
    ]);
    
    // For now, return success with message
    // Later: Return actual availability data from GeoQuote
    wp_send_json_success([
        'message' => 'Thank you! We\'ll check availability and contact you within 24 hours.',
        'redirect' => false, // Set to results page URL when ready
    ]);
}

/**
 * Save lead to database (simple CRM)
 *
 * @param array $lead_data
 * @return int Lead ID
 */
function citysyncai_save_lead($lead_data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'citysyncai_leads';
    
    // Create table if it doesn't exist
    citysyncai_create_leads_table();
    
    $wpdb->insert(
        $table_name,
        [
            'lead_type' => $lead_data['type'] ?? 'form',
            'business_name' => $lead_data['business_name'] ?? '',
            'email' => $lead_data['email'] ?? '',
            'phone' => $lead_data['phone'] ?? '',
            'address' => $lead_data['address'] ?? '',
            'city' => $lead_data['city'] ?? '',
            'state' => $lead_data['state'] ?? '',
            'additional_data' => json_encode($lead_data['additional'] ?? []),
            'source_url' => $_SERVER['HTTP_REFERER'] ?? '',
            'status' => 'new',
            'created_at' => current_time('mysql'),
        ],
        ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
    );
    
    return $wpdb->insert_id;
}

/**
 * Create leads table
 */
function citysyncai_create_leads_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'citysyncai_leads';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        lead_type varchar(50) NOT NULL,
        business_name varchar(255) DEFAULT '',
        email varchar(255) DEFAULT '',
        phone varchar(50) DEFAULT '',
        address text,
        city varchar(100) DEFAULT '',
        state varchar(50) DEFAULT '',
        additional_data longtext,
        source_url varchar(500) DEFAULT '',
        status varchar(50) DEFAULT 'new',
        notes text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY email (email),
        KEY status (status),
        KEY created_at (created_at)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Create table on activation
register_activation_hook(__FILE__, 'citysyncai_create_leads_table');

