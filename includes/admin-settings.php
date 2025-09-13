<?php
/**
 * Admin settings panel for CitySyncAI plugin.
 */

function citysyncai_register_settings() {
    register_setting('citysyncai_options', 'citysyncai_api_provider');
    register_setting('citysyncai_options', 'citysyncai_api_key');
    register_setting('citysyncai_options', 'citysyncai_schema_type');

    add_settings_section('citysyncai_main', 'CitySyncAI Settings', null, 'citysyncai');

    add_settings_field('api_provider', 'AI Provider', 'citysyncai_api_provider_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('api_key', 'API Key', 'citysyncai_api_key_field', 'citysyncai', 'citysyncai_main');
    add_settings_field('schema_type', 'Schema Type', 'citysyncai_schema_type_field', 'citysyncai', 'citysyncai_main');
}
add_action('admin_init', 'citysyncai_register_settings');

function citysyncai_api_provider_field() {
    $value = get_option('citysyncai_api_provider', 'gemini');
    echo '<select name="citysyncai_api_provider">
        <option value="gemini"' . selected($value, 'gemini', false) . '>Gemini</option>
        <option value="openai"' . selected($value, 'openai', false) . '>OpenAI</option>
        <option value="claude"' . selected($value, 'claude', false) . '>Claude</option>
    </select>';
}

function citysyncai_api_key_field() {
    $value = esc_attr(get_option('citysyncai_api_key', ''));
    echo '<input type="text" name="citysyncai_api_key" value="' . $value . '" size="50" />';
}

function citysyncai_schema_type_field() {
    $value = get_option('citysyncai_schema_type', 'LocalBusiness');
    echo '<select name="citysyncai_schema_type">
        <option value="LocalBusiness"' . selected($value, 'LocalBusiness', false) . '>LocalBusiness</option>
        <option value="Service"' . selected($value, 'Service', false) . '>Service</option>
        <option value="Event"' . selected($value, 'Event', false) . '>Event</option>
        <option value="Review"' . selected($value, 'Review', false) . '>Review</option>
    </select>';
}

function citysyncai_add_settings_page() {
    add_options_page(
        'CitySyncAI Settings',
        'CitySyncAI',
        'manage_options',
        'citysyncai',
        'citysyncai_render_settings_page'
    );
}
add_action('admin_menu', 'citysyncai_add_settings_page');

function citysyncai_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>CitySyncAI Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('citysyncai_options');
            do_settings_sections('citysyncai');
            submit_button();
            ?>
        </form>

        <h2>Schema Preview</h2>
        <?php
        $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
        if ($post_id) {
            $response = wp_remote_get(rest_url("citysyncai/v1/schema?id={$post_id}"));
            if (!is_wp_error($response)) {
                $body = wp_remote_retrieve_body($response);
                echo '<pre style="background:#f9f9f9;padding:1em;border:1px solid #ccc;">' . htmlentities($body, ENT_NOQUOTES, 'UTF-8') . '</pre>';
            } else {
                echo '<p>Unable to load schema preview.</p>';
            }
        } else {
            echo '<p>No post ID provided for preview. Append <code>?post=123</code> to the URL.</p>';
        }
        ?>
    </div>
    <?php
}