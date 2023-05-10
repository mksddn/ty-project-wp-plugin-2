<?php
add_filter('manage_post_posts_columns', 'typp_admin_columns');
add_filter('manage_page_posts_columns', 'typp_admin_columns');
function typp_admin_columns($column_array)
{
  $column_array['typp_name'] = 'TY Project Player';
  $column_array['typp_id'] = 'TY Project Player ID';
  $column_array['typp_type'] = 'Player Type';
  $column_array['typp_position'] = 'Player Position';
  return $column_array;
}

add_action('manage_posts_custom_column', 'typp_populate_admin_columns', 10, 2);
function typp_populate_admin_columns($column_name, $post_id)
{
  switch ($column_name) {
    case 'typp_name': {
        echo get_post_meta($post_id, 'typp_name', true);
        break;
      }
      case 'typp_id': {
        echo get_post_meta($post_id, 'typp_id', true);
        break;
      }
      case 'typp_type': {
        echo get_post_meta($post_id, 'typp_type', true);
        break;
      }
    case 'typp_position': {
        // $typp_p_val = get_post_meta($post_id, 'typp_position', true);
        // $typp_p_name = ($typp_p_val == 'Before Content') ? 'Before Content'
        //   : (($typp_p_val == 'After 1st Paragraph') ? 'After 1st Paragraph'
        //     : (($typp_p_val == 'After 1st Paragraph') ? 'After 1st Paragraph'
        //       : (($typp_p_val == 'After Content') ? 'After Content'
        //         : '')));
        // echo $typp_p_name;
        echo get_post_meta($post_id, 'typp_position', true);
        break;
      }
  }
}
