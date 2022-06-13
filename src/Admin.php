<?php

/**
 * For maintaining the CSS file cache.
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.1.2
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

class Admin
{
    protected $tailpress;

    public function __construct($tailpress)
    {
        $this->tailpress = $tailpress;
    }

    public function enqueue_scripts()
    {
        $cdn_name = $this->tailpress->name . '-cdn';
        $screen = get_current_screen();
        if (is_admin() && $screen->is_block_editor()) {
            wp_enqueue_script(
                $cdn_name,
                $this->tailpress->assets_js . 'tw-3.0.24.js'
            );
            wp_add_inline_script($cdn_name, "        
                tailwind.config = {
                    corePlugins: {
                        preflight: false,
                    }
                }
            ", 'after');
        }
    }
}
