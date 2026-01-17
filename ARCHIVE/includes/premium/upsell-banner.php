<?php
function citysyncai_show_upsell_banner() {
    if (!defined('CITYSYNC_PREMIUM')) {
        echo '<div class="notice notice-info"><p><strong>Upgrade to CitySyncAI Pro</strong> for bulk sync, advanced AI, and priority support. <a href="https://citysyncai.com/pro">Learn more</a></p></div>';
    }
}
add_action('admin_notices', 'citysyncai_show_upsell_banner');