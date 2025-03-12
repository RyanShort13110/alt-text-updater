<?php
/*
Plugin Name: Alt Tag Updater
Plugin URI: http://yourwebsite.com/alt-tag-updater
Description: Automatically updates alt tags for media library images and dynamically applies alt attributes on the frontend.
Version: 1.0.9
Author: Ryan S.
Author URI: http://yourwebsite.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: alt-tag-updater
Domain Path: /languages
*/

if (!defined('WPINC')) {
    die;
}

define('ALT_TAG_UPDATER_VERSION', '1.0.9');

function activate_alt_tag_updater() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-alt-tag-updater-activator.php';
    Alt_Tag_Updater_Activator::activate();
}

function deactivate_alt_tag_updater() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-alt-tag-updater-deactivator.php';
    Alt_Tag_Updater_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_alt_tag_updater');
register_deactivation_hook(__FILE__, 'deactivate_alt_tag_updater');

require_once plugin_dir_path(__FILE__) . 'includes/class-alt-tag-updater.php';

class Alt_Tag_Updater {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'settings_init']);
        add_action('add_attachment', [$this, 'update_media_alt_tag']);
        add_filter('wp_get_attachment_image_attributes', [$this, 'update_attachment_image_alt'], 10, 2);
        add_filter('the_content', [$this, 'update_images_in_post_content'], 10, 1);
        add_action('admin_post_bulk_update_alt_tags', [$this, 'bulk_update_existing_images']);
        add_action('wp_footer', [$this, 'force_alt_tags_with_js']);
    }

    public function add_admin_menu() {
        add_options_page('Alt Tag Updater', 'Alt Tag Updater', 'manage_options', 'alt_tag_updater', [$this, 'settings_page']);
    }

    public function settings_init() {
        register_setting('altTagUpdater', 'alt_tag_updater_keywords');

        add_settings_section(
            'alt_tag_updater_section',
            __('Keyword Settings', 'alt-tag-updater'),
            null,
            'altTagUpdater'
        );

        add_settings_field(
            'alt_tag_updater_keywords',
            __('Keywords (comma-separated)', 'alt-tag-updater'),
            [$this, 'keywords_render'],
            'altTagUpdater',
            'alt_tag_updater_section'
        );
    }

    public function keywords_render() {
        $keywords = get_option('alt_tag_updater_keywords', '');
        echo "<input type='text' name='alt_tag_updater_keywords' value='" . esc_attr($keywords) . "' style='width: 100%;' />";
    }

    public function settings_page() {
        echo '<form action="options.php" method="post">';
        settings_fields('altTagUpdater');
        do_settings_sections('altTagUpdater');
        submit_button();
        echo '</form>';

        echo '<hr>';
        echo '<h2>Bulk Update Alt Tags</h2>';
        echo '<p>Click the button below to update all existing images in the Media Library with new alt tags.</p>';
        echo '<form action="' . esc_url(admin_url('admin-post.php')) . '" method="post">';
        echo '<input type="hidden" name="action" value="bulk_update_alt_tags">';
        echo '<input type="submit" value="Update All Images" class="button button-primary">';
        echo '</form>';
    }

    public function update_media_alt_tag($post_ID) {
        $this->apply_alt_tag_to_media($post_ID);
    }

    public function bulk_update_existing_images() {
        $args = [
            'post_type'   => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
        ];
        $media_query = new WP_Query($args);

        foreach ($media_query->posts as $attachment) {
            $this->apply_alt_tag_to_media($attachment->ID);
        }

        wp_redirect(admin_url('options-general.php?page=alt_tag_updater&updated=true'));
        exit;
    }

    private function apply_alt_tag_to_media($post_ID) {
        $keywords = get_option('alt_tag_updater_keywords', '');
        if (empty($keywords)) {
            return;
        }

        $keywords_array = array_map('trim', explode(',', $keywords));
        $keyword = $keywords_array[array_rand($keywords_array)];

        update_post_meta($post_ID, '_wp_attachment_image_alt', esc_attr($keyword));
    }

    public function update_attachment_image_alt($attr, $attachment) {
        $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);

        if (empty($alt_text)) {
            $keywords = get_option('alt_tag_updater_keywords', '');
            if (!empty($keywords)) {
                $keywords_array = array_map('trim', explode(',', $keywords));
                $alt_text = esc_attr($keywords_array[array_rand($keywords_array)]);
                update_post_meta($attachment->ID, '_wp_attachment_image_alt', $alt_text);
            }
        }

        $attr['alt'] = esc_attr($alt_text);
        return $attr;
    }

    public function update_images_in_post_content($content) {
        return preg_replace_callback('/<img[^>]+>/i', function ($matches) {
            $keywords = get_option('alt_tag_updater_keywords', '');
            if (empty($keywords)) {
                return $matches[0]; // No change if no keywords are set
            }
    
            $keywords_array = array_map('trim', explode(',', $keywords));
            $keyword = esc_attr($keywords_array[array_rand($keywords_array)]);
    
            // Check if the image already has an empty alt tag
            if (preg_match('/alt=""/', $matches[0])) {
                return preg_replace('/alt=""/', 'alt="' . $keyword . '"', $matches[0]);
            }
    
            // If the image has no alt attribute at all, add one
            if (!preg_match('/alt="[^"]*"/', $matches[0])) {
                return preg_replace('/<img/', '<img alt="' . $keyword . '"', $matches[0]);
            }
    
            return $matches[0];
        }, $content);
    }
    

    public function force_alt_tags_with_js() {
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            let images = document.querySelectorAll("img");
            let keywords = "<?php echo esc_js(get_option('alt_tag_updater_keywords', 'Default Alt Text')); ?>".split(",");
    
            if (keywords.length > 0) {
                images.forEach((img, index) => {
                    let keyword = keywords[index % keywords.length].trim();
    
                    // If alt is missing OR empty, replace it
                    if (!img.hasAttribute("alt") || img.getAttribute("alt").trim() === "") {
                        img.setAttribute("alt", keyword);
                    }
    
                    // Handle lazy-loaded images
                    if (img.hasAttribute("data-src") && !img.hasAttribute("src")) {
                        img.setAttribute("src", img.getAttribute("data-src"));
                    }
                });
            }
        });
        </script>
        <?php
    }
    
}

new Alt_Tag_Updater();
