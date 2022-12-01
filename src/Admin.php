<?php

/**
 * For maintaining the admin interface.
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.3.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

use Blockpress\Tailpress\Settings;
use Blockpress\Tailpress\Cache;

class Admin
{
    protected $tailpress;
    protected $admin_nonce_name;

    public function __construct($tailpress)
    {
        $this->tailpress = $tailpress;
        $this->settings = new Settings($this->tailpress);
        $this->admin_nonce_name = $this->tailpress->name . '_clear_cache';
        add_action(
            'wp_ajax_tailpress_ajax_clear_cache',
            array($this, 'clear_cache')
        );
    }

    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        if (is_admin() && $screen->is_block_editor()) {
            $this->tailpress->enqueue_tailwind_assets();
        }

        if (is_admin() && $screen->id === 'settings_page_tailpress-settings') {
            wp_enqueue_script($this->admin_nonce_name, $this->tailpress->assets_js . 'clear-cache.js', array(), '1.0');
            wp_localize_script(
                $this->admin_nonce_name,
                $this->admin_nonce_name . '_ajax_object',
                array(
                    'ajax_url'   => admin_url('admin-ajax.php'),
                    'ajax_nonce' => wp_create_nonce(
                        $this->admin_nonce_name
                    )
                ),
            );
        }
    }

    public function clear_cache()
    {
        check_ajax_referer($this->admin_nonce_name, '_ajax_nonce');
        (new Cache($this->tailpress))->purge_entire_cache();
        echo json_encode("OK");
        die();
    }
}
