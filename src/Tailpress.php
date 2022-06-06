<?php

namespace Blockpress\Tailpress;

use Blockpress\Tailpress\Frontend;
use Blockpress\Tailpress\Admin;
use Blockpress\Tailpress\Cache;

class Tailpress
{
    protected $name = 'tailpress';
    protected $version;
    protected $plugin_path;
    protected $plugin_url;
    protected $assets_js;
    protected $ajax_nonce_name;
    protected $css_cache_dir;

    public function __construct($file, $version)
    {
        $this->version = $version;
        $this->plugin_path = plugin_dir_path($file);
        $this->plugin_url = plugin_dir_url($file);
        $this->assets_js = $this->plugin_url . 'js/';
        $this->ajax_nonce_name = $this->name . '_ajax_nonce';
        $this->css_cache_dir = wp_get_upload_dir()['basedir'] . '/' . $this->name;
    }

    public function __get($property)
    {
        return $this->{$property};
    }

    public function boot()
    {
        $frontend = new Frontend($this);
        $admin = new Admin($this);
        (new Cache($this))->boot();

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
        add_action(
            'admin_enqueue_scripts',
            array(
                $admin, 'enqueue_scripts'
            )
        );
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

    public function log($message, $shouldNotDie = true)
    {
        error_log(print_r($message, true));
        if ($shouldNotDie) {
            exit;
        }
    }
}
