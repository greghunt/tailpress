<?php

/**
 * Responsible for managing the frontend of the website.
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.3.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

class Frontend
{
    protected $tailpress;

    public function __construct($tailpress)
    {
        $this->tailpress = $tailpress;
    }

    public function enqueue_scripts()
    {
        //TODO replcae cache
        $hash = $this->tailpress->get_url_hash();
        $files = glob($this->tailpress->css_cache_dir . "/$hash.*.css");
        if (isset($files[0]) && file_exists($files[0])) {
            $file_cache = $files[0];
            add_action('wp_head', function () use ($file_cache) {
                echo sprintf(
                    '<style id="%s">%s</style>',
                    esc_attr($this->tailpress->name),
                    esc_html(file_get_contents($file_cache))
                );
            }, 50);
        }
    }
}
