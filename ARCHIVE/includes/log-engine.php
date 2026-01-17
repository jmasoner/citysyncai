<?php
/**
 * Logging Engine for CitySyncAI
 * Handles error logging and tracking
 */

defined('ABSPATH') || exit;

/**
 * Log an error message
 *
 * @param string $message Error message to log
 */
function citysyncai_log_error($message) {
    if (function_exists('error_log')) {
        error_log('[CitySyncAI] ' . $message);
    }
    
    // Store errors in WordPress option for admin display
    $errors = get_option('citysyncai_errors', []);
    $errors[] = [
        'time' => current_time('mysql'),
        'message' => $message
    ];
    
    // Keep only last 100 errors
    if (count($errors) > 100) {
        $errors = array_slice($errors, -100);
    }
    
    update_option('citysyncai_errors', $errors);
}

/**
 * Get recent errors
 *
 * @param int $limit Number of errors to retrieve
 * @return array Array of error messages
 */
function citysyncai_get_errors($limit = 50) {
    $errors = get_option('citysyncai_errors', []);
    return array_slice($errors, -$limit);
}

/**
 * Clear all logged errors
 */
function citysyncai_clear_errors() {
    delete_option('citysyncai_errors');
}

/**
 * Log API usage for tracking
 *
 * @param string $provider AI provider name
 * @param string $status Success or error status
 */
function citysyncai_log_api_usage($provider, $status = 'success') {
    $usage = get_option('citysyncai_api_usage', []);
    $today = date('Y-m-d');
    
    if (!isset($usage[$today])) {
        $usage[$today] = [];
    }
    
    if (!isset($usage[$today][$provider])) {
        $usage[$today][$provider] = ['success' => 0, 'error' => 0];
    }
    
    $usage[$today][$provider][$status]++;
    
    // Keep only last 30 days
    $usage = array_slice($usage, -30, 30, true);
    
    update_option('citysyncai_api_usage', $usage);
}


