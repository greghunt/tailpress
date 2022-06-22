<?php

/**
 * For maintaining the admin interface.
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.2.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

use Blockpress\Tailpress\Settings;

class Admin
{
    protected $tailpress;

    public function __construct($tailpress)
    {
        $this->tailpress = $tailpress;
        $this->settings = new Settings($this->tailpress);
    }

    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        if (is_admin() && $screen->is_block_editor()) {
            $this->tailpress->enqueue_tailwind_assets();
        }
    }
}
