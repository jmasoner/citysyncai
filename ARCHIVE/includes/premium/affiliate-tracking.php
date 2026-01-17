<?php
function citysyncai_track_affiliate() {
    if (isset($_GET['ref'])) {
        setcookie('citysyncai_affiliate', sanitize_text_field($_GET['ref']), time() + 30 * DAY_IN_SECONDS, '/');
    }
}
add_action('init', 'citysyncai_track_affiliate');