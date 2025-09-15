add_action('add_meta_boxes', function () {
    add_meta_box(
        'citysyncai_ai_meta',
        'CitySyncAI AI Content Override',
        'citysyncai_render_ai_meta_box',
        ['post', 'page'],
        'normal',
        'default'
    );
});

function citysyncai_render_ai_meta_box($post) {
    $custom_ai = get_post_meta($post->ID, '_citysyncai_custom_ai', true);
    wp_nonce_field('citysyncai_save_ai', 'citysyncai_ai_nonce');

    echo '<textarea name="citysyncai_custom_ai" style="width:100%;height:200px;">' . esc_textarea($custom_ai) . '</textarea>';
    echo '<p><em>Paste custom AI-generated content here. Leave blank to use global engine.</em></p>';
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['citysyncai_ai_nonce']) || !wp_verify_nonce($_POST['citysyncai_ai_nonce'], 'citysyncai_save_ai')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['citysyncai_custom_ai'])) {
        update_post_meta($post_id, '_citysyncai_custom_ai', sanitize_textarea_field($_POST['citysyncai_custom_ai']));
    }
});