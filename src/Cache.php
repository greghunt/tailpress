<?php

/**
 * For maintaining the CSS file cache. Checks if page content has changed
 * and generates class list and corresponding CSS file if it has.
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.3.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

use Blockpress\Tailpress\PageCache;
use Blockpress\Tailpress\Tailpress;

class Cache
{
    protected $dir;
    protected $url_hash;

    public function __construct()
    {
        $this->dir = self::get_dir();
        $this->url_hash = self::get_url_hash();
        if (!file_exists($this->dir)) {
            wp_mkdir_p($this->dir);
        }
    }

    public static function get_url_hash($url = null)
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

    public static function get_dir()
    {
        return wp_get_upload_dir()['basedir'] . '/' . Tailpress::PLUGIN_NAME;
    }

    public static function purge_entire_cache()
    {
        foreach (glob(self::get_dir() . "/*.*.*") as $file) {
            unlink($file);
        }
    }

    public function run($buffer)
    {
        $pageCache = new PageCache($buffer);
        $css = new CssCache($pageCache);

        if (!$pageCache->cache_is_valid()) {
            $pageCache->push();
        }

        if (!$css->cache_is_valid())
            $css->save();

        return $buffer;
    }
}
