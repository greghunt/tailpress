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
        wp_enqueue_script($cdn_name, 'https://cdn.tailwindcss.com');
        wp_add_inline_script($cdn_name, "        
            tailwind.config = {
                corePlugins: {
                    preflight: false,
                }
            }
        ", 'after');
    }
}
