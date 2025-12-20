<?php
/**
 * City Data Handler for CitySyncAI
 * Provides functions to work with city data
 */

defined('ABSPATH') || exit;

/**
 * Get city data from JSON file
 *
 * @param string $city City name (e.g., "New York")
 * @param string $state State abbreviation (e.g., "NY")
 * @return array|false City data array or false if not found
 */
function citysyncai_get_city_data($city, $state = '') {
    $cities = citysyncai_load_city_data();
    
    if (empty($state)) {
        // Search by city name only (return first match)
        foreach ($cities as $city_data) {
            if (strtolower($city_data['city']) === strtolower($city)) {
                return $city_data;
            }
        }
    } else {
        // Search by both city and state
        foreach ($cities as $city_data) {
            if (strtolower($city_data['city']) === strtolower($city) && 
                strtoupper($city_data['state']) === strtoupper($state)) {
                return $city_data;
            }
        }
    }
    
    return false;
}

/**
 * Load all city data from JSON file
 *
 * @return array Array of city data
 */
function citysyncai_load_city_data() {
    static $cached_data = null;
    
    if ($cached_data !== null) {
        return $cached_data;
    }
    
    $json_file = CITYSYNCAI_DIR . 'data/us-cities-sample.json';
    
    if (!file_exists($json_file)) {
        citysyncai_log_error("City data file not found: {$json_file}");
        return [];
    }
    
    $json_content = file_get_contents($json_file);
    $data = json_decode($json_content, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        citysyncai_log_error("Error parsing city data JSON: " . json_last_error_msg());
        return [];
    }
    
    $cached_data = $data;
    return $data;
}

/**
 * Get cities by state
 *
 * @param string $state State abbreviation (e.g., "NY")
 * @return array Array of cities in that state
 */
function citysyncai_get_cities_by_state($state) {
    $all_cities = citysyncai_load_city_data();
    $state_cities = [];
    
    foreach ($all_cities as $city) {
        if (strtoupper($city['state']) === strtoupper($state)) {
            $state_cities[] = $city;
        }
    }
    
    return $state_cities;
}

/**
 * Get all states with cities
 *
 * @return array Array of state abbreviations
 */
function citysyncai_get_states() {
    $all_cities = citysyncai_load_city_data();
    $states = [];
    
    foreach ($all_cities as $city) {
        if (!in_array($city['state'], $states)) {
            $states[] = $city['state'];
        }
    }
    
    sort($states);
    return $states;
}

/**
 * Format city name with state (e.g., "New York, NY")
 *
 * @param string $city City name
 * @param string $state State abbreviation
 * @return string Formatted city string
 */
function citysyncai_format_city_name($city, $state = '') {
    if (empty($state)) {
        return $city;
    }
    return "{$city}, {$state}";
}

/**
 * Get total number of cities in database
 *
 * @return int Number of cities
 */
function citysyncai_get_city_count() {
    return count(citysyncai_load_city_data());
}

/**
 * Get cities by tier (exclude tier 1, get tier 2/3)
 * Tier 1 = Major metros (NYC, LA, Chicago, etc.) - EXCLUDED
 * Tier 2 = Secondary metros (Austin, Nashville, etc.) - INCLUDED
 * Tier 3 = Smaller metros/suburbs - INCLUDED
 *
 * @param array $included_tiers Array of tiers to include (default: [2, 3])
 * @param int $limit Maximum number of cities to return (default: 500)
 * @return array Array of city data sorted by population
 */
function citysyncai_get_cities_by_tier($included_tiers = [2, 3], $limit = 500) {
    $all_cities = citysyncai_load_city_data();
    $filtered_cities = [];
    
    // Tier 1 cities to exclude (major metros)
    $tier1_cities = [
        'New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix',
        'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose',
        'Miami', 'Seattle', 'Boston', 'Detroit', 'Minneapolis',
        'Denver', 'Washington', 'Atlanta', 'Portland', 'Las Vegas'
    ];
    
    foreach ($all_cities as $city) {
        // Exclude tier 1 cities
        if (in_array($city['city'], $tier1_cities)) {
            continue;
        }
        
        // Determine tier based on population
        $population = $city['population'] ?? 0;
        $tier = citysyncai_determine_city_tier($population);
        
        // Include if in desired tiers
        if (in_array($tier, $included_tiers)) {
            $city['tier'] = $tier;
            $filtered_cities[] = $city;
        }
    }
    
    // Sort by population (descending) - largest tier 2/3 cities first
    usort($filtered_cities, function($a, $b) {
        $pop_a = $a['population'] ?? 0;
        $pop_b = $b['population'] ?? 0;
        return $pop_b <=> $pop_a;
    });
    
    // Limit results
    return array_slice($filtered_cities, 0, $limit);
}

/**
 * Determine city tier based on population
 *
 * @param int $population
 * @return int Tier (1, 2, or 3)
 */
function citysyncai_determine_city_tier($population) {
    if ($population >= 500000) {
        return 2; // Tier 2: Major secondary metros
    } elseif ($population >= 100000) {
        return 3; // Tier 3: Smaller metros/suburbs
    } else {
        return 4; // Tier 4: Small cities (can be included if needed)
    }
}

/**
 * Get top N tier 2/3 cities for bulk generation
 *
 * @param int $count Number of cities to return (default: 500)
 * @return array Array of city data
 */
function citysyncai_get_top_tier2_tier3_cities($count = 500) {
    return citysyncai_get_cities_by_tier([2, 3], $count);
}

