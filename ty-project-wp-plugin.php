<?php

/**
 * Plugin Name:       TYLR Player
 * Description:       Gutenberg Sidebar for TYLR Player
 * Requires at least: 5.3
 * Requires PHP:      7.0
 * Version:           0.2.17
 * Author:            Ty Project
 * Author URI:        https://www.tylr.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('ABSPATH')) {
  exit;
}

function tytylr_enqueue_scripts()
{
  wp_enqueue_script(
    'tytylr-gutenberg-sidebar-js',
    plugins_url('build/index.js', __FILE__),
    array('wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data')
  );
}
add_action('enqueue_block_editor_assets', 'tytylr_enqueue_scripts');

function tytylr_enqueue_styles()
{
  wp_enqueue_style('tytylr-css', plugins_url('tytylr-styles.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'tytylr_enqueue_styles');

require_once plugin_dir_path(__FILE__) . 'post-meta.php';
require_once plugin_dir_path(__FILE__) . 'post-columns.php';
require_once plugin_dir_path(__FILE__) . 'quick-bulk-edit.php';
require_once plugin_dir_path(__FILE__) . 'settings-page.php';
require_once plugin_dir_path(__FILE__) . 'render.php';
