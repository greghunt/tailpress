<?php

/**
 * Responsible for managing plugin settings
 *
 * @link              https://greghunt.dev/posts/tailwind-for-wordpress/
 * @since             0.4.3
 * @package           Tailpress
 *
 * @wordpress-plugin
 */

namespace FreshBrewedWeb\Tailpress;

use FreshBrewedWeb\Tailpress\Plugin;
use FreshBrewedWeb\Tailpress\Cache;

class Settings
{

    private $plugin;
    private $page = "tailpress";
    private $page_title;
    private $options_name;
    private $options;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->add_page('settings', 'TailPress Settings');
        $this->options_name = $this->plugin->name . '_plugin_options';
        $this->options = get_option($this->options_name);

        add_action('update_option_tailpress_plugin_options', function () {
            (new Cache($this->plugin))->purge_entire_cache();
        }, 10, 0);

        register_activation_hook(
            $this->plugin->plugin_file,
            array($this, 'activate')
        );
        register_deactivation_hook(
            $this->plugin->plugin_file,
            array($this, 'deactivate')
        );
    }

    public function activate()
    {
        $default_config = '{
            "preflight": false
        }';

        if (!get_option($this->options_name)) {
            add_option($this->options_name, array('config' => $default_config));
        }
    }

    public function deactivate()
    {
        if ($this->get_option('cleanup') == '1') {
            delete_option($this->options_name);
        }
    }

    public function add_page($slug, $title = null)
    {
        $this->page = implode('-', [
            $this->plugin->name, $slug
        ]);
        $this->page_title = $title ?? $slug;
    }

    public function get_page_name()
    {
        return $this->page;
    }

    public function get_options_name()
    {
        return $this->options_name;
    }

    public function get_option($slug = null)
    {
        if (is_null($slug))
            return $this->options;

        return $this->options[$slug] ?? null;
    }

    public function get_plugin_options()
    {
        return $this->options;
    }

    public function registerSettings($config)
    {
        foreach ($config as $section => $c) {
            add_settings_section(
                $section,
                $c['label'],
                [$this, 'renderSection'],
                $this->page
            );

            foreach ($c['fields'] as $field) {
                add_settings_field(
                    $this->plugin->name . $field['name'],
                    $field['label'],
                    [$this, 'renderSetting'],
                    $this->page,
                    $section,
                    ['section' => $section, 'field' => $field]
                );
            }
        }
    }

    public function renderSetting($args)
    {
        extract($args);
        $id = $this->field_id_from_name($field['name']);
        $name = $this->field_name_from_name($field['name']);
        $slug = strtolower($field['name']);

        require $this->plugin->plugin_path .
            "templates/settings/$section/$slug.php";
    }

    public function renderSection($args)
    {
        extract($args);
        $path = $this->plugin->plugin_path . "templates/sections/$id.php";

        if (file_exists($path)) include $path;
    }

    public function destroy()
    {
        delete_option($this->options_name);
    }

    public function renderPage()
    {
        do_action(implode('_', ['before', $this->page, 'page']));
?>
        <div class="wrap" style="max-width:80em;">
            <h2><?php echo $this->page_title ?></h2>
            <form action="options.php" method="post">
                <?php
                settings_fields($this->options_name);
                do_settings_sections($this->page);
                ?>
                <hr />
                <div style="text-align:right">
                    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
                </div>
            </form>
        </div>
<?php
        do_action(implode('_', ['after', $this->page, 'page']));
    }

    public function field_id_from_name($name)
    {
        return $this->plugin->name . '_' . $name;
    }

    public function field_name_from_name($name)
    {
        return "{$this->options_name}[$name]";
    }

    public function add_menu_item()
    {
        add_options_page(
            __('TailPress Settings', 'tailpress'),
            __('TailPress', 'tailpress'),
            'manage_options',
            $this->get_page_name(),
            [$this, 'renderPage']
        );
    }

    public function init()
    {
        register_setting($this->options_name, $this->options_name);
        $this->registerSettings($this->getSettingsConfig());
    }

    private function getSettingsConfig()
    {
        return [
            'general_settings' => [
                'label' => 'General',
                'fields' => [
                    [
                        'name' => 'config',
                        'label' => 'Tailwind Config',
                    ],
                    [
                        'name' => 'clear-cache',
                        'label' => 'Cache',
                    ],
                    [
                        'name' => 'cleanup',
                        'label' => 'Cleanup',
                    ],
                ],
            ],
        ];
    }
}
