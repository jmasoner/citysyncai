<?php
/* Template for AI-generated city page */
$city = get_post_meta(get_the_ID(), 'citysyncai_city', true);
$type = get_post_meta(get_the_ID(), 'citysyncai_type', true);
$content = citysyncai_generate_content($city, $type);
?>

<div class="citysyncai-page">
  <h1>Discover <?php echo esc_html($city); ?></h1>
  <div class="citysyncai-content">
    <?php echo $content; ?>
  </div>
</div>