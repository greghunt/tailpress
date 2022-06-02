<?php

namespace Blockpress\Wptw;

use Sabberworm\CSS\Parser as CssParser;

class Frontend
{
    public function enqueue_scripts()
    {
        $hash = get_url_hash();
        $files = glob(WPTW_CSS_DIR . "/$hash.*.css");
        $file_cache = $files[0];

        if (file_exists($file_cache)) {
            add_action('wp_head', function () use ($file_cache) {
                $css = file_get_contents($file_cache);
                $id = WPTW_PLUGIN_NAME;
                echo "<style id='$id'>$css</style>";
            }, 50);
        } else {
            wp_enqueue_script(WPTW_PLUGIN_NAME . '-md5', WPTW_PLUGIN_JS . 'md5.js');
            wp_enqueue_script(WPTW_CDN_SCRIPT_NAME, 'https://cdn.tailwindcss.com');
            wp_add_inline_script(WPTW_CDN_SCRIPT_NAME, '        
                tailwind.config = {
                    corePlugins: {
                        preflight: false,
                    }
                }
            ', 'after');
            wp_enqueue_script(WPTW_PLUGIN_NAME, WPTW_PLUGIN_JS . 'cache-tw.js', WPTW_CDN_SCRIPT_NAME, array(WPTW_PLUGIN_NAME . '-md5', WPTW_CDN_SCRIPT_NAME));

            wp_localize_script(
                WPTW_PLUGIN_NAME,
                WPTW_PLUGIN_NAME . '_ajax_object',
                array(
                    'ajax_url'   => admin_url('admin-ajax.php'),
                    'ajax_nonce' => wp_create_nonce(WPTW_AJAX_NONCE_NAME)
                ),
            );
        }
    }

    public function cache_styles()
    {
        // check nonce
        check_ajax_referer(WPTW_AJAX_NONCE_NAME, '_ajax_nonce');
        $url_hash =  get_url_hash(sanitize_url($_POST['url']));
        $page_hash = sanitize_text_field($_POST['hash']);

        if (!file_exists(WPTW_CSS_DIR)) {
            wp_mkdir_p(WPTW_CSS_DIR);
        }

        if (isset($_POST['css']) && !empty($_POST['css'])) {
            $filename = "$url_hash.$page_hash.css";
            $parser = new CssParser($_POST['css']);
            $cssDocument = $parser->parse();

            file_put_contents(
                WPTW_CSS_DIR . "/$filename",
                $cssDocument->render()
            );
        }

        echo json_encode(['hash' => $filename]);
        die();
    }
}
