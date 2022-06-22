<div class="form-field">
    <textarea id="<?php echo $id ?>" name="<?php echo $name ?>" rows="20"><?php echo $this->get_option('config'); ?></textarea>
    <p>
        This will override your <a href="https://tailwindcss.com/docs/configuration">Tailwind settings</a>. By default, the preflight plugin is disabled so it doesn't interfere with the base styles of your site.
    </p>
</div>