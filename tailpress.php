<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.2.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 * 
 * Plugin Name:       TailPress
 * Plugin URI:        https://blockpress.dev/tailwind-wordpress/
 * Description:       Seamlessly add Tailwind to your WordPress site without any build steps.
 * Version:           0.2.0
 * Author:            blockpress
 * Author URI:        https://blockpress.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wptw
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

require 'vendor/autoload.php';

use Blockpress\Tailpress\Tailpress;

(new Tailpress(__FILE__, '0.1.2'))->boot();
