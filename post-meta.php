<?php
if (!defined('ABSPATH')) {
  exit;
}

$arr = array('typp_id', 'typp_name', 'typp_type', 'typp_position');
foreach ($arr as &$value) {
  register_post_meta('', $value, [
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
    'auth_callback' => function () {
      return current_user_can('edit_posts');
    }
  ]);
}
