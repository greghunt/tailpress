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

// URL loads and is assigned a unique URL HASH
// buffer loads and is assigned a unique PAGE HASH
// check all caches that start with URL HASH
// if PAGE HASH doesn't match, regerate CSV
class Cache
{
    protected $tailpress;
    protected $url_hash;
    protected $page_hash;
    protected $classnames;
    protected $filename;
    protected $filepath;

    public function __construct($tailpress)
    {
        $this->tailpress = $tailpress;
    }

    public function check_buffer($buffer)
    {
        if (is_user_logged_in()) {
            return $buffer;
        }

        if (!file_exists($this->tailpress->css_cache_dir)) {
            wp_mkdir_p($this->tailpress->css_cache_dir);
        }

        $this->classnames = $this->get_classnames_from_buffer($buffer);
        $this->url_hash = $this->tailpress->get_url_hash();
        $this->page_hash = $this->get_page_hash($this->classnames);
        $this->filename = implode('.', [
            $this->url_hash, $this->page_hash, 'csv'
        ]);
        $this->filepath = $this->tailpress->css_cache_dir . "/" . $this->filename;

        if ($this->cache_is_valid()) {
            return $buffer;
        }

        $this->push();

        return $buffer;
    }

    public function purge_entire_cache()
    {
        $files = glob($this->tailpress->css_cache_dir . "/*.*.*");
        foreach ($files as $file)
            unlink($file);
    }

    private function cache_is_valid()
    {
        return file_exists($this->filepath) &&
            file_exists(str_replace('.csv', '.css', $this->filepath));
    }

    private function push()
    {
        $this->purge_invalid_cache();
        $this->save_page_cache();
    }

    private function purge_invalid_cache()
    {
        $files = glob($this->tailpress->css_cache_dir . "/{$this->url_hash}.*.*");
        foreach ($files as $file) {
            unlink($file);
        }
    }

    private function save_page_cache()
    {
        file_put_contents($this->filepath, $this->get_page_cache());
        $this->save_css_cache();
    }

    private function save_css_cache()
    {
        $config = json_decode(
            $this->tailpress->settings->get_option('config') ?? '{}'
        );

        $css_path = str_replace('.csv', '.css', $this->filepath);
        $req = new \WP_Http();
        $result = $req->post('https://tailwind.restedapi.com/api/v1', [
            'body' => json_encode([
                'text' => $this->get_page_cache(),
                'options' => $config,
            ])
        ]);
        $css = $result['body'];
        file_put_contents($css_path, $css);
    }

    private function get_page_hash()
    {
        return md5($this->get_page_cache($this->classnames));
    }

    private function get_page_cache()
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
