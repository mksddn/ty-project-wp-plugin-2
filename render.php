<?php

if (!is_admin()) {
  add_action('template_redirect', 'typp_get_vars');
  add_action('template_redirect', 'typp_show_player');
}

function typp_get_vars()
{
  $post_id = get_queried_object_id();
  $typp_id = get_post_meta($post_id, 'typp_id', true);
  $typp_name = get_post_meta($post_id, 'typp_name', true);
  // $typp_type = get_post_meta($id, 'typp_type', true);
  $typp_position = get_post_meta($post_id, 'typp_position', true);
  if (!empty($typp_id)) {
    return [$post_id, $typp_id, $typp_name, $typp_position];
  }
}

function typp_get_player_info()
{
  [$post_id, $typp_id, $typp_name, $typp_position] = typp_get_vars();
  return [$typp_code = '<script defer id="' . $typp_id . '">Widget.init("' . $typp_id . '")</script>', $typp_position];
}

function typp_show_player()
{
  [$typp_code, $typp_position] = typp_get_player_info();
  if ($typp_position == 'After 1st Paragraph') {
    echo $typp_position;
  } elseif ($typp_position == 'After Content') {
    function typp_add_after_content($content = '')
    {
      [$typp_code] = typp_get_player_info();
      $content .= $typp_code;
      return $content;
    }
    add_filter('the_content', 'typp_add_after_content');
  } else {
    function typp_add_before_content($content)
    {
      [$typp_code] = typp_get_player_info();
      $custom_content = $typp_code;
      $custom_content .= $content;
      return $custom_content;
    }
    add_filter('the_content', 'typp_add_before_content');
  }
}


function add_main_typp_script()
{
  wp_enqueue_script('script-name', 'https://ty.mailstone.net/widget/player.js');
}
add_action('wp_enqueue_scripts', 'add_main_typp_script');
