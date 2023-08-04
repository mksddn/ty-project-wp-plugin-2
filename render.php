<?php
if (!defined('ABSPATH')) {
  exit;
}

if (!is_admin()) {
  add_action('template_redirect', 'tytylr_get_vars');
  add_action('template_redirect', 'tytylr_show_player');
}

function tytylr_add_main_typp_script()
{
  $post_id = get_queried_object_id();
  if (get_post_meta($post_id, 'typp_id', true)) {
    wp_enqueue_script('script-name', 'https://dashboard.tylr.com/widget/player.js');
  }
}
add_action('wp_enqueue_scripts', 'tytylr_add_main_typp_script');

function tytylr_get_vars()
{
  $post_id = get_queried_object_id();
  $typp_id = get_post_meta($post_id, 'typp_id', true);
  $typp_name = get_post_meta($post_id, 'typp_name', true);
  $typp_type = get_post_meta($post_id, 'typp_type', true);
  $typp_position = get_post_meta($post_id, 'typp_position', true);
  if (!empty($typp_id)) {
    return [$post_id, $typp_id, $typp_name, $typp_type, $typp_position];
  }
}

function tytylr_get_player_info()
{
  [$post_id, $typp_id, $typp_name, $typp_type, $typp_position] = tytylr_get_vars();
  if ($typp_type === 'static') {
    return [$typp_code = '<style>#ty-project-widget{margin:1rem 0;}</style><script defer id="' . $typp_id . '">Widget.init("' . $typp_id . '")</script>', $typp_position];
  } else {
    return [$typp_code = '<script defer id="' . $typp_id . '">Widget.init("' . $typp_id . '")</script>', $typp_position];
  }
}

function tytylr_show_player()
{
  [$typp_code, $typp_position] = tytylr_get_player_info();
  function tytylr_insert_after_paragraph($insertion, $paragraph_id, $content)
  {
    $closing_p = '</p>';
    $paragraphs = explode($closing_p, $content);
    foreach ($paragraphs as $index => $paragraph) {
      if (trim($paragraph)) {
        $paragraphs[$index] .= $closing_p;
      }
      if ($paragraph_id == $index + 1) {
        $paragraphs[$index] .= $insertion;
      }
    }
    return implode('', $paragraphs);
  }
  if ($typp_position == 'After 1st Paragraph') {
    add_filter('the_content', 'tytylr_insert_post_player');
    function tytylr_insert_post_player($content)
    {
      [$typp_code] = tytylr_get_player_info();
      return tytylr_insert_after_paragraph($typp_code, 1, $content);
      return $content;
    }
  } elseif ($typp_position == 'After 2nd Paragraph') {
    add_filter('the_content', 'tytylr_insert_post_player');
    function tytylr_insert_post_player($content)
    {
      [$typp_code] = tytylr_get_player_info();
      return tytylr_insert_after_paragraph($typp_code, 2, $content);
      return $content;
    }
  } elseif ($typp_position == 'After Content') {
    function tytylr_add_after_content($content = '')
    {
      [$typp_code] = tytylr_get_player_info();
      $content .= $typp_code;
      return $content;
    }
    add_filter('the_content', 'tytylr_add_after_content');
  } elseif ($typp_position == 'Before Content') {
    function tytylr_add_before_content($content)
    {
      [$typp_code] = tytylr_get_player_info();
      $custom_content = $typp_code;
      $custom_content .= $content;
      return $custom_content;
    }
    add_filter('the_content', 'tytylr_add_before_content');
  } elseif ($typp_position == 'After Title') {
    function tytylr_filter_the_title($title)
    {
      [$typp_code] = tytylr_get_player_info();
      $custom_title = $typp_code;
      $title .= $custom_title;
      return $title;
    }
    add_filter('the_title', 'tytylr_filter_the_title');
  } else {
    function tytylr_add_after_content($content = '')
    {
      [$typp_code] = tytylr_get_player_info();
      $content .= $typp_code;
      return $content;
    }
    add_filter('the_content', 'tytylr_add_after_content');
  }
}
