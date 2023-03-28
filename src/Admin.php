<?php

/**
 * For maintaining the admin interface.
 *
 * @link              https://greghunt.dev/posts/tailwind-for-wordpress/
 * @since             0.4.3
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace FreshBrewedWeb\Tailpress;

use FreshBrewedWeb\Tailpress\Cache;
use FreshBrewedWeb\Tailpress\Plugin;

class Admin
{
    protected $plugin;
    protected $admin_nonce_name;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->admin_nonce_name = $this->plugin->name . '_clear_cache';
        add_action(
            'wp_ajax_tailpress_ajax_clear_cache',
            array($this, 'clear_cache')
        );
    }

    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        if (is_admin() && $screen->is_block_editor()) {
            $scripts = $this->plugin->get_admin_scripts();
            $name = $this->plugin->name . '_twind_admin';
            wp_enqueue_script($name, $scripts['main']);
            wp_add_inline_script($name, $scripts['setup']);
        }

        if (is_admin() && $screen->id === 'settings_page_tailpress-settings') {
            wp_enqueue_script('tailpress-json-editor', $this->plugin->assets_js . 'vendor/json-editor.0.2.4.js', array(), '0.2.4');

            wp_enqueue_script($this->admin_nonce_name, $this->plugin->assets_js . 'clear-cache.js', array(), '1.0');
            wp_localize_script(
                $this->admin_nonce_name,
                $this->admin_nonce_name . '_ajax_object',
                array(
                    'ajax_url'   => admin_url('admin-ajax.php'),
                    'ajax_nonce' => wp_create_nonce(
                        $this->admin_nonce_name
                    )
                )
            );
        }
    }

    public function clear_cache()
    {
        check_ajax_referer($this->admin_nonce_name, '_ajax_nonce');
        (new Cache($this->plugin))->purge_entire_cache();
        echo json_encode("OK");
        die();
    }
}
