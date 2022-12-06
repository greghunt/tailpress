<?php

/**
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.3.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

use Blockpress\Tailpress\PageCache;
use Blockpress\Tailpress\Settings;

class CssCache
{
    protected $pageCache;

    public function __construct(PageCache $cache)
    {
        $this->pageCache = $cache;
    }

    public function cache_is_valid()
    {
        return file_exists($this->pageCache->get_key() . '.css');
    }

    public function save()
    {
        $config = json_decode(
            Settings::get_option('config') ?? '{}'
        );

        $css_path = str_replace('.csv', '.css', $this->filepath);
        $req = new \WP_Http();
        $result = $req->post('https://tailwind.restedapi.com/api/v1', [
            'body' => json_encode([
                'text' => $this->get(),
                'options' => $config,
            ])
        ]);
        $css = $result['body'];
        file_put_contents($css_path, $css);
    }
}
