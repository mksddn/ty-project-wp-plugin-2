<?php
if (!defined('ABSPATH')) {
  exit;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'tytylr_settings_link');
function tytylr_settings_link(array $links)
{
  $url = get_admin_url() . "options-general.php?page=typp";
  $settings_link = '<a href="' . $url . '">' . __('Settings', 'textdomain') . '</a>';
  $links[] = $settings_link;
  return $links;
}

function tytylr_add_settings_page()
{
  add_submenu_page(
    'options-general.php',
    'TYLR Player Settings',
    'TYLR Player',
    'manage_options',
    'typp',
    'tytylr_show_settings_form'
  );
}
add_action('admin_menu', 'tytylr_add_settings_page', 25);

function tytylr_add_settings()
{
  $typp_user_email = 'typp_user_email';
  $typp_user_password = 'typp_user_password';

  register_setting('typp_settings', $typp_user_email);
  register_setting('typp_settings', $typp_user_password);
  register_setting('typp_settings', 'tytylr_token', array('show_in_rest' => true));
  register_setting('typp_settings', 'tytylr_refresh_token');

  add_settings_section('typp_settings_section', 'Use your credentials to log in to the TYLR dashboard', '', 'typp');

  add_settings_field(
    $typp_user_email,
    'Email',
    'tytylr_add_email_field',
    'typp',
    'typp_settings_section',
    array(
      'label_for' => $typp_user_email,
      'name' => $typp_user_email,
    )
  );
  add_settings_field(
    $typp_user_password,
    'Password',
    'tytylr_add_password_field',
    'typp',
    'typp_settings_section',
    array(
      'label_for' => $typp_user_password,
      'name' => $typp_user_password,
    )
  );
}
add_action('admin_init', 'tytylr_add_settings');

function tytylr_add_email_field($args)
{
  $value = get_option($args['name']);
  printf(
    '<input type="text" id="%s" name="%s" value="%s" />',
    esc_attr($args['name']),
    esc_attr($args['name']),
    esc_html(strval($value))
  );
}
function tytylr_add_password_field($args)
{
  $value = get_option($args['name']);
  printf(
    '<input type="password" id="%s" name="%s" value="%s" />',
    esc_attr($args['name']),
    esc_attr($args['name']),
    esc_html(strval($value))
  );
}


function tytylr_show_settings_form()
{

  if (get_option('typp_user_email') && get_option('typp_user_password')) {
    tytylr_auth(get_option('typp_user_email'), get_option('typp_user_password'));
  }

  printf(
    '<div class="wrap">
			<h1>%s</h1>
			<form method="post" action="options.php">',
    esc_html(get_admin_page_title())
  );
  settings_fields('typp_settings');
  do_settings_sections('typp');
  submit_button();
  printf('</form></div>');

  if (isset($_POST['typp_user_email']) && isset($_POST['typp_user_password'])) {
    tytylr_auth(sanitize_email($_POST['typp_user_email']), sanitize_text_field($_POST['typp_user_password']));
  }
}

function tytylr_auth($email, $password)
{
  $url = 'https://dashboard.tylr.com/api/auth/login';
  $response = wp_remote_post($url, array(
    'headers' => array('Content-Type: application/x-www-form-urlencoded'),
    'body'    => array('email' =>  $email, 'password' => $password),
  ));

  if ($response['response']['code'] == '200') {
    update_option('tytylr_token', wp_remote_retrieve_headers($response)['access-token']);
    update_option('tytylr_refresh_token', wp_remote_retrieve_headers($response)['refresh-token']);
    printf('<div class="notice notice-success is-dismissible"><p>You are authorized</p></div>');
    // fetch_players();
  } else {
    $responceData = (!is_wp_error($response)) ? json_decode(wp_remote_retrieve_body($response), true) : null;
    printf(
      '<div class="notice notice-error is-dismissible"><p>%s</p>
    <p>You can change your password on <a href="https://dashboard.tylr.com/" target="_blank">dashboard.tylr.com</a></p></div>',
      esc_html($responceData['message'])
    );
  }
}
