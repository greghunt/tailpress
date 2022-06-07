<?php

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
