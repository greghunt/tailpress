<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.1.0
 * @package           wptw
 *
 * @wordpress-plugin
 * 
 * Plugin Name:       TailPress
 * Plugin URI:        https://blockpress.dev/tailwind-wordpress/
 * Description:       Seamlessly add Tailwind to your WordPress site without any build steps.
 * Version:           0.1.0
 * Author:            blockpress
 * Author URI:        https://blockpress.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wptw
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('WPTW_PLUGIN_NAME', 'tailpress');
define('WPTW_CDN_SCRIPT_NAME', WPTW_PLUGIN_NAME . '-cdn');
define('WPTW_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WPTW_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPTW_PLUGIN_JS', WPTW_PLUGIN_URL . 'js/');
define('WPTW_AJAX_NONCE_NAME', WPTW_PLUGIN_NAME . '_ajax_nonce');
define('WPTW_CSS_DIR', wp_get_upload_dir()['basedir'] . '/' . WPTW_PLUGIN_NAME);

require 'vendor/autoload.php';
require 'src/bootstrap.php';
