<?php

/**
 * Plugin Name:       TY Project Player
 * Plugin URI:        https://plugin-url.net
 * Description:       Gutenberg Sidebar for TY Project
 * Requires at least: 5.3
 * Requires PHP:      7.0
 * Version:           0.2.5
 * Author:            Afterlogic
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('ABSPATH')) {
  exit;
}

function typp_enqueue_scripts()
{
  wp_enqueue_script(
    'typp-gutenberg-sidebar-js',
    plugins_url('build/index.js', __FILE__),
    array('wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data')
  );
}
add_action('enqueue_block_editor_assets', 'typp_enqueue_scripts');

function typp_enqueue_styles()
{
  wp_enqueue_style('typp-css', plugins_url('typp-styles.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'typp_enqueue_styles');

require_once plugin_dir_path(__FILE__) . 'post-meta.php';
require_once plugin_dir_path(__FILE__) . 'post-columns.php';
require_once plugin_dir_path(__FILE__) . 'quick-bulk-edit.php';
require_once plugin_dir_path(__FILE__) . 'settings-page.php';
require_once plugin_dir_path(__FILE__) . 'render.php';
