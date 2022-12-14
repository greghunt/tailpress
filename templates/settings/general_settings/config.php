<div class="form-field">
    <textarea id="<?php echo $id ?>" name="<?php echo $name ?>" rows="20"><?php echo $this->get_option('config'); ?></textarea>
    <p>
        This will override your <a href="https://tailwindcss.com/docs/configuration">Tailwind settings</a>. By default, the preflight plugin is disabled so it doesn't interfere with the base styles of your site.
    </p>
    <p>
        This must be configured as a valid JSON object and follow this <a href="https://twind.dev/handbook/configuration.html">configuration</a>.
    </p>
</div>