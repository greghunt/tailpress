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
    protected $name = 'tailpress';
    protected $version;
    protected $settings;
    protected $plugin_path;
    protected $plugin_url;
    protected $assets_js;
    protected $ajax_nonce_name;
    protected $css_cache_dir;
    protected $main_script_name;

    public function __construct($file, $version)
    {
        $this->version = $version;
        $this->plugin_path = plugin_dir_path($file);
        $this->plugin_url = plugin_dir_url($file);
        $this->assets_js = $this->plugin_url . 'js/';
        $this->ajax_nonce_name = $this->name . '_ajax_nonce';
        $this->css_cache_dir = wp_get_upload_dir()['basedir'] . '/' . $this->name;
        $this->main_script_name = $this->name . '-cdn';
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
            $cache = new Cache($this);
            ob_start(array($cache, 'check_buffer'));
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

    public function get_url_hash($url = null)
    {
        if (is_null($url)) {
            $host = $_SERVER['HTTP_HOST'];
            $uri = parse_url($_SERVER['REQUEST_URI']);
        } else {
            $uri = parse_url(sanitize_url($url));
            $host = $uri['host'];
        }

        $path = $uri['path'];
        $query = $uri['query'] ?? '';

        return md5($host . $path . $query);
    }

    public static function log($message, $shouldNotDie = true)
    {
        error_log(print_r($message, true));
        if ($shouldNotDie) {
            exit;
        }
    }
}
