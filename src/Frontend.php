<?php

/**
 * Responsible for managing the frontend of the website.
 *
 * @link              https://blockpress.dev/tailwind-wordpress/
 * @since             0.2.0
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace Blockpress\Tailpress;

class Frontend
{
    protected $tailpress;

    public function __construct($tailpress)
    {
        $this->tailpress = $tailpress;
    }

    public function enqueue_scripts()
    {
        $hash = $this->tailpress->get_url_hash();
        $files = glob($this->tailpress->css_cache_dir . "/$hash.*.css");
        if (isset($files[0]) && file_exists($files[0])) {
            $file_cache = $files[0];
            add_action('wp_head', function () use ($file_cache) {
                echo sprintf(
                    '<style id="%s">%s</style>',
                    esc_attr($this->tailpress->name),
                    esc_html(file_get_contents($file_cache))
                );
            }, 50);
        } else {
            $md5_name = $this->tailpress->name . '-md5';
            wp_enqueue_script(
                $md5_name,
                $this->tailpress->assets_js . 'md5.js'
            );

            $this->tailpress->enqueue_tailwind_assets();

            wp_enqueue_script(
                $this->tailpress->name,
                $this->tailpress->assets_js . 'cache.js',
                array($md5_name, $this->tailpress->main_script_name)
            );

            wp_localize_script(
                $this->tailpress->name,
                $this->tailpress->name . '_ajax_object',
                array(
                    'ajax_url'   => admin_url('admin-ajax.php'),
                    'ajax_nonce' => wp_create_nonce(
                        $this->tailpress->ajax_nonce_name
                    )
                ),
            );
        }
    }

    public function cache_styles()
    {
        check_ajax_referer($this->tailpress->ajax_nonce_name, '_ajax_nonce');
        $url_hash =  $this->tailpress->get_url_hash($_POST['url']);
        $page_hash = sanitize_text_field($_POST['hash']);
        if (!file_exists($this->tailpress->css_cache_dir)) {
            wp_mkdir_p($this->tailpress->css_cache_dir);
        }

        if (isset($_POST['css']) && !empty($_POST['css'])) {
            $filename = "$url_hash.$page_hash.css";
            $sanitized_css = sanitize_textarea_field(stripslashes($_POST['css']));
            file_put_contents(
                $this->tailpress->css_cache_dir . "/$filename",
                $sanitized_css
            );
        }

        echo json_encode(['hash' => $filename]);
        die();
    }
}
