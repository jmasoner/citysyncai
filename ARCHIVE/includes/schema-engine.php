<?php
/**
 * Schema Engine for CitySyncAI
 * Handles global templates and per-post overrides.
 */

defined('ABSPATH') || exit;

// 🔹 Add meta box for per-post schema override
add_action('add_meta_boxes', function () {
    add_meta_box(
        'citysyncai_schema_meta',
        'CitySyncAI Schema Override',
        'citysyncai_render_schema_meta_box',
        ['post', 'page'],
        'normal',
        'default'
    );
});

function citysyncai_render_schema_meta_box($post) {
    $custom_schema = get_post_meta($post->ID, '_citysyncai_custom_schema', true);
    wp_nonce_field('citysyncai_save_schema', 'citysyncai_schema_nonce');

    echo '<textarea name="citysyncai_custom_schema" style="width:100%;height:200px;">' . esc_textarea($custom_schema) . '</textarea>';
    echo '<p><em>Paste custom JSON-LD schema here. Leave blank to use global template.</em></p>';
}

// 🔹 Save schema override
add_action('save_post', function ($post_id) {
    if (!isset($_POST['citysyncai_schema_nonce']) || !wp_verify_nonce($_POST['citysyncai_schema_nonce'], 'citysyncai_save_schema')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['citysyncai_custom_schema'])) {
        update_post_meta($post_id, '_citysyncai_custom_schema', sanitize_textarea_field($_POST['citysyncai_custom_schema']));
    }
});
add_action('add_meta_boxes', function () {
    add_meta_box(
        'citysyncai_schema_type_meta',
        'CitySyncAI Schema Type',
        'citysyncai_render_schema_type_meta_box',
        ['post', 'page'],
        'side',
        'default'
    );
});

function citysyncai_render_schema_type_meta_box($post) {
    $selected = get_post_meta($post->ID, '_citysyncai_schema_type', true) ?: get_option('citysyncai_schema_type', 'LocalBusiness');
    $types = ['LocalBusiness', 'Event', 'Service', 'Review'];

    echo '<select name="citysyncai_schema_type">';
    foreach ($types as $type) {
        echo "<option value='$type'" . selected($selected, $type, false) . ">$type</option>";
    }
    echo '</select>';
}

add_action('save_post', function ($post_id) {
    if (isset($_POST['citysyncai_schema_type'])) {
        update_post_meta($post_id, '_citysyncai_schema_type', sanitize_text_field($_POST['citysyncai_schema_type']));
    }
});