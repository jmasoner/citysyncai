<?php
function citysyncai_import_settings($json) {
    $settings = json_decode($json, true);
    if (!is_array($settings)) return false;

    foreach ($settings as $key => $value) {
        update_option($key, sanitize_text_field($value));
    }

    return true;
}