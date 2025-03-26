<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://youbehero.com
 * @since      1.0.0
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/admin/partials
 */
?>
<div class="wrap">
    <h1>YouBeHero API Settings</h1>

    <?php if ($ybh_token) : ?>
        <form method="post" action="options.php">
            <?php settings_fields('ybh_settings_group'); ?>
            <?php do_settings_sections('ybh-settings'); ?>
            <label for="ybh_token">API Token:</label>
            <input type="text" id="ybh_token" name="ybh_token" value="<?php echo esc_attr($ybh_token); ?>" style="width: 300px;" />
            <?php submit_button('Save Token'); ?>
        </form>
    <?php else : ?>
        <button id="fetch-token" class="button button-primary">Get Token</button>
        <p id="token-status"></p>
    <?php endif; ?>
</div>
