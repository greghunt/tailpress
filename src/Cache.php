<?php

namespace Blockpress\Wptw;

class Cache
{
    protected $priority = 10;

    public function __construct()
    {
        \add_action('template_redirect', function () {
            ob_start(array($this, 'callback_function'));
        }, $this->priority);

        \add_action('shutdown', function () {
            ob_end_flush();
        }, -1 * $this->priority);
    }


    public function callback_function($buffer)
    {
        $url_hash = get_url_hash();
        $files = glob(WPTW_CSS_DIR . "/$url_hash.*.css");
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
        $classnames = array_values(array_unique(array_flatten(array_map(function ($m) {
            return explode(' ', $m[1]);
        }, $matches))));
        $page_hash = md5(implode(' ', $classnames));

        $filename = "$url_hash.$page_hash.css";
        foreach ($files as $cache) {
            if (!$this->ends_with($cache, $filename)) {
                unlink($cache);
            }
        }
    }
}
