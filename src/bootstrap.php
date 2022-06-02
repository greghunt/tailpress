<?php

namespace Blockpress\Wptw;

use Blockpress\Wptw\Frontend;
use Blockpress\Wptw\Admin;

if (!function_exists('write_log')) {
    function write_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

function array_flatten($array = null)
{
    $result = array();

    if (!is_array($array)) {
        $array = func_get_args();
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, array_flatten($value));
        } else {
            $result = array_merge($result, array($key => $value));
        }
    }

    return $result;
}

function get_url_hash($url = null)
{
    if (is_null($url)) {
        $host = $_SERVER['HTTP_HOST'];
        $uri = parse_url($_SERVER['REQUEST_URI']);
    } else {
        $uri = parse_url($url);
        $host = $uri['host'];
    }

    $path = $uri['path'];
    $query = $uri['query'];
    return md5($host . $path . $query);
}

$frontend = new Frontend;
\add_action('wp_enqueue_scripts', array($frontend, 'enqueue_scripts'));
\add_action('wp_ajax_nopriv_wptw_ajax', array($frontend, 'cache_styles'));
\add_action('admin_enqueue_scripts', array((new Admin), 'enqueue_scripts'));
