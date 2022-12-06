<?php

/**
 * The main plugin class
 *
 * @link              https://wpblock.dev/tailwind-wordpress/
 * @since             0.3.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

use Blockpress\Tailpress\Admin;
use Blockpress\Tailpress\Cache;
use Blockpress\Tailpress\Frontend;
use Blockpress\Tailpress\Settings;

class Tailpress
{
    const PLUGIN_NAME = 'tailpress';
    protected $version;
    protected $settings;
    protected $plugin_path;
    protected $plugin_url;
    protected $assets_js;
    protected $ajax_nonce_name;
    protected $main_script_name;

    public function __construct($file, $version)
    {
        $this->version = $version;
        $this->plugin_path = plugin_dir_path($file);
        $this->plugin_url = plugin_dir_url($file);
        $this->assets_js = $this->plugin_url . 'js/';
        $this->ajax_nonce_name = self::PLUGIN_NAME . '_ajax_nonce';
        $this->main_script_name = self::PLUGIN_NAME . '-cdn';
        $this->settings = new Settings($this);
    }

    public function __get($property)
    {
        return $this->{$property};
    }

    private function pageBufferCache()
    {
        $priority = 10;

        add_action('template_redirect', function () {
            if (!is_user_logged_in()) {
                ob_start(function ($buffer) {
                    (new Cache)->run($buffer);
                });
            }
        }, $priority);

        add_action('shutdown', function () {
            if (ob_get_length() > 0) {
                ob_end_flush();
            }
        }, -1 * $priority);
    }

    public function boot()
    {
        $frontend = new Frontend($this);
        $admin = new Admin($this);
        $this->pageBufferCache();

        /**
         * Frontend Hooks
         */
        add_action(
            'wp_enqueue_scripts',
            array($frontend, 'enqueue_scripts')
        );
        add_action(
            'wp_ajax_nopriv_tailpress_ajax',
            array(
                $frontend, 'cache_styles'
            )
        );

        /**
         * Admin Hooks
         */
        add_action(
            'admin_enqueue_scripts',
            array(
                $admin, 'enqueue_scripts'
            )
        );
        add_action(
            'enqueue_block_editor_assets',
            array(
                $admin, 'enqueue_scripts'
            )
        );
        add_action('admin_menu', array($this->settings, 'add_menu_item'));
        add_action('admin_init', array($this->settings, 'init'));
    }

    public static function log($message, $shouldNotDie = true)
    {
        error_log(print_r($message, true));
        if ($shouldNotDie) {
            exit;
        }
    }
}
