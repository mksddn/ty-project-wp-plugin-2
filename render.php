<?php
if (!defined('ABSPATH')) {
  exit;
}
if (!is_admin()) {
  add_action('template_redirect', 'tytylr_get_vars');
}
function tytylr_get_vars()
{
  $post_id = get_queried_object_id();
  $typp_id = get_post_meta($post_id, 'typp_id', true);
  $typp_name = get_post_meta($post_id, 'typp_name', true);
  $typp_type = get_post_meta($post_id, 'typp_type', true);
  $typp_position = get_post_meta($post_id, 'typp_position', true);
  if (!empty($typp_id)) {
    return [$typp_id, $typp_name, $typp_type, $typp_position];
  }
}

add_action('wp_enqueue_scripts', 'tytylr_add_main_typp_script');
function tytylr_add_main_typp_script()
{
  [$typp_id, $typp_name, $typp_type, $typp_position] = tytylr_get_vars();
  if ($typp_id) {
    wp_enqueue_script('typp-script', 'https://dashboard.tylr.com/widget/player.js');
    wp_add_inline_script('typp-script', '
    const typp_id = "' . esc_html($typp_id) . '";
    const typp_type = "' . esc_html($typp_type) . '";
    const typp_position = "' . esc_html($typp_position) . '";
    ');
    wp_enqueue_script('typp-local-script', plugins_url('tytylr-scripts.js', __FILE__));
  }
}
