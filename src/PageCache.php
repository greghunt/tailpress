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

class PageCache
{
    protected $cache;
    protected $page_hash;
    protected $classnames;
    protected $filename;
    protected $filepath;

    public function __construct($buffer, $cache)
    {
        $this->cache = $cache;
        $this->classnames = $this->get_classnames_from_buffer($buffer);
        $this->page_hash = $this->hash($this->classnames);
        $this->filename = implode('.', [
            $this->url_hash, $this->page_hash, 'csv'
        ]);
        $this->filepath = $this->tailpress->css_cache_dir . "/" . $this->filename;
    }

    public function cache_is_valid()
    {
        return file_exists($this->filepath);
    }

    public function push()
    {
        $this->purge_invalid();
        $this->save();
    }

    public function purge_invalid()
    {
        $files = glob($this->tailpress->css_cache_dir . "/{$this->url_hash}.*.*");
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function get_key()
    {
        return $this->url_hash . '.' . $this->pageCache->hash();
    }

    public function save()
    {
        file_put_contents($this->filepath, $this->get());
    }

    public function hash()
    {
        return md5($this->get($this->classnames));
    }

    private function get()
    {
        return implode(PHP_EOL, $this->classnames);
    }

    private function get_classnames_from_buffer($buffer)
    {
        $re = '/class="([^"]+)"/';
        preg_match_all($re, $buffer, $matches, PREG_SET_ORDER, 0);
        $classes = array_values(array_unique(
            $this->array_flatten(array_map(function ($m) {
                return explode(' ', strtolower($m[1]));
            }, $matches))
        ));
        asort($classes);
        return array_filter($classes);
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
