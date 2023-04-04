<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://greghunt.dev/posts/tailwind-for-wordpress/
 * @since             0.4.3
 * @package           Tailpress
 *
 * @wordpress-plugin
 * 
 * Plugin Name:       TailPress
 * Plugin URI:        https://greghunt.dev/posts/tailwind-for-wordpress/
 * Description:       Seamlessly add Tailwind to your WordPress site without any build steps.
 * Version:           0.4.4
 * Author:            freshbrewedweb
 * Author URI:        https://greghunt.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wptw
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

require __DIR__ . '/vendor/autoload.php';

use FreshBrewedWeb\Tailpress\Plugin;

function tailpress_log($message)
{
    error_log(print_r($message, true));
}

(new Plugin(__FILE__, '0.4.3'))->boot();
