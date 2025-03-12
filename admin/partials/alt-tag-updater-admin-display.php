<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/admin/partials
 */
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="post" action="options.php">
        <?php
            settings_fields('altTagUpdater');
            do_settings_sections('altTagUpdater');
            submit_button();
        ?>
    </form>
</div>
