<?php

/**
 * For maintaining the CSS file cache.
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.1.1
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

class Cache
{
    protected $priority = 10;
    protected $tailpress;

    public function __construct($tailpress)
    {
        $this->tailpress = $tailpress;
    }

    public function boot()
    {
        add_action('template_redirect', function () {
            ob_start(array($this, 'check_caches'));
        }, $this->priority);

        add_action('shutdown', function () {
            if (ob_get_length() > 0) {
                ob_end_flush();
            }
        }, -1 * $this->priority);
    }

    public function check_caches($buffer)
    {
        $url_hash = $this->tailpress->get_url_hash();
        $files = glob($this->tailpress->css_cache_dir . "/$url_hash.*.css");
        if (!empty($files)) {
            $this->invalidate_caches($url_hash, $files, $buffer);
        }

        return $buffer;
    }

    private function ends_with($haystack, $needle)
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }

    private function invalidate_caches($url_hash, $files, $buffer)
    {
        $re = '/class="([^"]+)"/';
        preg_match_all($re, $buffer, $matches, PREG_SET_ORDER, 0);
        $classnames = array_values(array_unique(
            $this->array_flatten(array_map(function ($m) {
                return explode(' ', $m[1]);
            }, $matches))
        ));
        $page_hash = md5(implode(' ', $classnames));

        $filename = "$url_hash.$page_hash.css";
        foreach ($files as $cache) {
            if (!$this->ends_with($cache, $filename)) {
                unlink($cache);
            }
        }
    }

    private function array_flatten($array = null)
    {
        $result = array();

        if (!is_array($array)) {
            $array = func_get_args();
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }

        return $result;
    }
}
