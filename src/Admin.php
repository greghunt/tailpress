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
        wp_enqueue_script(
            $this->tailpress->name . '-cdn',
            'https://cdn.tailwindcss.com'
        );
    }
}
