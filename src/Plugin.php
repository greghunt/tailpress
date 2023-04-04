<?php

/**
 * The main plugin class
 *
 * @link              https://greghunt.dev/posts/tailwind-for-wordpress/
 * @since             0.4.4
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace FreshBrewedWeb\Tailpress;

use FreshBrewedWeb\Tailpress\Admin;
use FreshBrewedWeb\Tailpress\Cache;
use FreshBrewedWeb\Tailpress\Frontend;
use FreshBrewedWeb\Tailpress\Settings;

class Plugin
{
    protected $name = 'tailpress';
    protected $version;
    protected $settings;
    protected $plugin_file;
    protected $plugin_path;
    protected $plugin_url;
    protected $assets_js;
    protected $ajax_nonce_name;
    protected $main_script_name;

    public function __construct($file, $version)
    {
        $this->version = $version;
        $this->plugin_file = $file;
        $this->plugin_path = plugin_dir_path($this->plugin_file);
        $this->plugin_url = plugin_dir_url($file);
        $this->assets_js = $this->plugin_url . 'js/';
        $this->ajax_nonce_name = $this->name . '_ajax_nonce';
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
            if (!is_user_logged_in() && !is_admin()) {
                ob_start(function ($buffer) {
                    (new Cache($this))->run($buffer);
                    return $buffer;
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
        add_action('wp_enqueue_scripts', array($frontend, 'enqueue_scripts'));
        add_action(
            'wp_ajax_nopriv_tailpress_ajax',
            array(
                $frontend, 'cache_styles'
            )
        );

        /**
         * Admin Hooks
         */
        add_action('admin_enqueue_scripts', array($admin, 'enqueue_scripts'));
        add_action('admin_menu', array($this->settings, 'add_menu_item'));
        add_action('admin_init', array($this->settings, 'init'));
    }

    /**
     * Link: https://cdn.jsdelivr.net/npm/@twind/core@1
     * Docs: https://twind.style/installation#twind-cdn
     * When using presets, core file needs to be updated.
     */
    public function get_client_scripts()
    {
        $config = $this->settings->get_option('config');
        if (empty($config)) $config = '{}';

        $setup_script = "
            const options = $config
            twind.install({
                ...options,
                hash: (className) => className,
            })
        ";

        return [
            'main' => $this->assets_js . 'twind.cdn.1.0.5.js',
            'setup' => $setup_script
        ];
    }

    public function get_admin_scripts()
    {
        $config = $this->settings->get_option('config');
        if (empty($config)) $config = '{}';

        $setup_script = "
            const options = $config
            twind.install({
                ...options,
            })
        ";

        return [
            'main' => $this->assets_js . 'twind.cdn.1.0.8.js',
            'setup' => $setup_script
        ];
    }
}
