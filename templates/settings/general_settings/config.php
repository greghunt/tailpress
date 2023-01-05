<div class="form-field">
    <json-editor style=" height: 20em; width: calc(100% - 60px); padding:30px" id="json-editor" value='<?php echo esc_attr($this->get_option('config')); ?>'></json-editor>
    <input type="hidden" id="<?php echo $id ?>" name="<?php echo $name ?>" value='<?php echo esc_attr($this->get_option('config')); ?>' />
    <script>
        const $conf = document.getElementById('tailpress_config');
        document.getElementById('json-editor').addEventListener('keyup', (event) => {
            $conf.value = event.target.value;
            console.log(event.target.value);
        });
    </script>
    <p>
        This will override your <a href="https://tailwindcss.com/docs/configuration">Tailwind configuration settings</a>. This is mostly the same as you would configure any other Tailwind instance, but in JSON format. Core plugins can be defined at the top level, such as <code>{"preflight": false}</code>
    </p>
    <p>
        By default, the preflight plugin is disabled so it doesn't interfere with the base styles of your site. Remove or set to true to include it.
    </p>
</div>