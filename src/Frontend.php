<?php

/**
 * Responsible for managing the frontend of the website.
 *
 * @link              https://greghunt.dev/posts/tailwind-for-wordpress/
 * @since             0.3.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace FreshBrewedWeb\Tailpress;

class Frontend
{
    protected $plugin;
    protected $cache;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->cache = new Cache($this->plugin);
    }

    public function enqueue_scripts()
    {
        if ($css_path = $this->cache->get_css_path()) {
            add_action('wp_head', function () use ($css_path) {
                echo sprintf(
                    '<style id="%s">%s</style>',
                    esc_attr($this->plugin->name),
                    file_get_contents($css_path)
                );
            }, 50);
        } else {
            $scripts = $this->plugin->get_client_scripts();
            $name = $this->plugin->name . '_twind';
            wp_enqueue_script($name, $scripts['main']);
            wp_add_inline_script($name, $scripts['setup']);
        }
    }
}
