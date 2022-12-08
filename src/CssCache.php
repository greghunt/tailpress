<?php

/**
 *
 * @link              https://greghunt.dev/posts/tailwind-for-wordpress/
 * @since             0.3.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace FreshBrewedWeb\Tailpress;

use FreshBrewedWeb\Tailpress\PageCache;

class CssCache
{
    protected $key;
    protected $filename;
    protected $filepath;

    public function __construct(PageCache $pcache)
    {
        $cache = $pcache->get_cache();
        $this->key = $pcache->get_key();
        $this->filename = $this->key . '.css';
        $this->filepath = $cache->get_dir() . '/' . $this->filename;
        $this->settings = $cache->get_plugin()->settings;
        $this->classnames = $pcache->get_classnames();
    }

    public function cache_is_valid()
    {
        return file_exists($this->filepath);
    }

    public function get_path()
    {
        return $this->filepath;
    }

    public function save()
    {
        $config = json_decode(
            $this->settings->get_option('config') ?? '{}'
        );

        $req = new \WP_Http();
        $result = $req->post('https://tailwind.restedapi.com/api/v1', [
            'body' => json_encode([
                'text' => implode(' ', $this->classnames),
                'options' => $config,
            ])
        ]);
        $css = $result['body'];
        file_put_contents($this->filepath, $css);
    }
}
