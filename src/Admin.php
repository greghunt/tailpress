<?php

namespace Blockpress\Wptw;

class Admin
{
    public function enqueue_scripts()
    {
        wp_enqueue_script(WPTW_CDN_SCRIPT_NAME, 'https://cdn.tailwindcss.com');
    }
}
