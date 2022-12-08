<?php

/**
 * For maintaining the CSS file cache. Checks if page content has changed
 * and generates class list and corresponding CSS file if it has.
 *
 * @link              https://greghunt.dev/posts/tailwind-for-wordpress/
 * @since             0.3.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace FreshBrewedWeb\Tailpress;

use FreshBrewedWeb\Tailpress\PageCache;
use FreshBrewedWeb\Tailpress\Plugin;

class Cache
{
    protected $plugin;
    protected $dir;
    protected $url_hash;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->dir = $this->get_dir();
        $this->url_hash = $this->get_url_hash();
        if (!file_exists($this->dir)) {
            wp_mkdir_p($this->dir);
        }
    }

    public function get_url_hash($url = null)
    {
        if (is_null($url)) {
            $host = $_SERVER['HTTP_HOST'];
            $uri = parse_url($_SERVER['REQUEST_URI']);
        } else {
            $uri = parse_url(sanitize_url($url));
            $host = $uri['host'];
        }

        $path = $uri['path'];
        $query = $uri['query'] ?? '';

        return md5($host . $path . $query);
    }

    public function get_plugin()
    {
        return $this->plugin;
    }

    public function get_css_path()
    {
        $hash = $this->get_url_hash();
        $files = glob("{$this->dir}/{$hash}.*.css");
        if (!isset($files[0]) || !file_exists($files[0])) {
            return null;
        }

        return $files[0];
    }

    public function get_dir()
    {
        return wp_get_upload_dir()['basedir'] . '/' . $this->plugin->name;
    }

    public function purge_entire_cache()
    {
        foreach (glob($this->get_dir() . "/*.*.*") as $file) {
            unlink($file);
        }
    }

    public function run($buffer)
    {
        $pageCache = new PageCache($buffer, $this);
        $css = new CssCache($pageCache);

        if (!$pageCache->cache_is_valid()) {
            $pageCache->push();
        }

        if (!$css->cache_is_valid())
            $css->save();

        return $buffer;
    }
}
