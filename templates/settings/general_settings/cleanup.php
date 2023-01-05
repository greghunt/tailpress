<div class="form-field">
    <label for="<?php echo $id ?>">
        <input id="<?php echo $id ?>" name="<?php echo $name ?>" type="checkbox" id="users_can_register" value="1" <?php echo $this->get_option('cleanup') == '1' ? 'checked' : '' ?> />
        <span><?php esc_attr_e('Activating this setting will delete your saved options once you deactivate the plugin.', 'TailPress'); ?></span>
    </label>
</div>