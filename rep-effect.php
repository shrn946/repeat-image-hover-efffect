<?php
/*
Plugin Name: Repeat Image Hover Effect
Description: Adds a shortcode to display a repeating visual effect.
Version: 1.1
Author: WP Design Lab
*/

if (!defined('ABSPATH')) exit;

// Enqueue Scripts and Styles
function rep_effect_enqueue_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('rep-effect-style', $plugin_url . 'css/main-rep.css');
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js', [], null, true);
    wp_enqueue_script('rep-effect-script', $plugin_url . 'js/rep.js', ['gsap'], null, true);
    wp_enqueue_script('rep-sponsor', 'https://tympanus.net/codrops/adpacks/cda_sponsor.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'rep_effect_enqueue_assets');


// ============================
// Admin Menu & Settings
// ============================
function rep_effect_register_settings_menu() {
    add_options_page('Rep Effect Images', 'Rep Effect Images', 'manage_options', 'rep-effect-settings', 'rep_effect_settings_page');
}
add_action('admin_menu', 'rep_effect_register_settings_menu');

function rep_effect_settings_page() {
    ?>
    <div class="wrap">
        <h1>Repeat Image Hover Effect</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('rep_effect_settings');
            do_settings_sections('rep-effect-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function rep_effect_register_settings() {
    register_setting('rep_effect_settings', 'rep_effect_images', [
        'type' => 'array',
        'sanitize_callback' => 'rep_effect_sanitize_images',
    ]);

    add_settings_section('rep_effect_main_section', '', null, 'rep-effect-settings');

    add_settings_field(
        'rep_effect_images_field',
        'Upload Images',
        'rep_effect_images_field_callback',
        'rep-effect-settings',
        'rep_effect_main_section'
    );
}
add_action('admin_init', 'rep_effect_register_settings');

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('dashicons');
    wp_enqueue_media();
});

// ============================
// Settings Field Callback
// ============================
function rep_effect_images_field_callback() {
    $images = get_option('rep_effect_images', []);
    if (!is_array($images)) $images = [];

    ?>
    <style>
        .rep-effect-images-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .rep-effect-image-block {
            width: 220px;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fafafa;
            border-radius: 6px;
            position: relative;
        }
        .rep-effect-preview {
            width: 100%;
            height: 200px;
            overflow: hidden;
            border: 1px solid #ccc;
            background: #fff;
        }
        .rep-effect-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .rep-effect-remove-icon-wrap {
            position: absolute;
            top: 5px;
            right: 5px;
        }
        .rep-effect-remove-icon {
            color: #a00;
            cursor: pointer;
            font-size: 18px;
        }
        .rep-effect-remove-icon:hover {
            color: red;
        }
        .rep-effect-shortcode {
            display: block;
            margin-top: 10px;
            font-size: 12px;
        }
    </style>

    <div id="rep-effect-images-container" class="rep-effect-images-wrapper">
        <?php foreach ($images as $index => $url): ?>
            <?php if (!empty($url)): ?>
                <div class="rep-effect-image-block">
                    <div class="rep-effect-remove-icon-wrap">
                        <span class="dashicons dashicons-trash rep-effect-remove-icon" title="Remove"></span>
                    </div>
                    <input type="hidden" name="rep_effect_images[]" value="<?php echo esc_url($url); ?>" />
                    <div class="rep-effect-preview">
                        <img src="<?php echo esc_url($url); ?>" alt="Preview" />
                    </div>
                    <button class="button rep-effect-upload-button" style="margin-top:10px;">Upload</button>
                    <span class="rep-effect-shortcode">Shortcode: <code>[rep_effect id="<?php echo $index; ?>"]</code></span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <button type="button" class="button" id="add-new-rep-image" style="margin-top:15px;">Add Image</button>

    <script>
    jQuery(document).ready(function($) {
        function mediaUploader(input, preview) {
            const frame = wp.media({
                title: 'Select or Upload Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                input.val(attachment.url);
                preview.html('<img src="' + attachment.url + '" alt="Preview" />');
            });

            frame.open();
        }

        $(document).on('click', '.rep-effect-upload-button', function(e) {
            e.preventDefault();
            const block = $(this).closest('.rep-effect-image-block');
            const input = block.find('input[type="hidden"]');
            const preview = block.find('.rep-effect-preview');
            mediaUploader(input, preview);
        });

        $(document).on('click', '.rep-effect-remove-icon', function() {
            $(this).closest('.rep-effect-image-block').remove();
            updateShortcodeIndexes();
        });

        $('#add-new-rep-image').on('click', function() {
            const index = $('#rep-effect-images-container .rep-effect-image-block').length;
            const html = `
                <div class="rep-effect-image-block">
                    <div class="rep-effect-remove-icon-wrap">
                        <span class="dashicons dashicons-trash rep-effect-remove-icon" title="Remove"></span>
                    </div>
                    <input type="hidden" name="rep_effect_images[]" value="" />
                    <div class="rep-effect-preview"></div>
                    <button class="button rep-effect-upload-button" style="margin-top:10px;">Upload</button>
                    <span class="rep-effect-shortcode">Shortcode: <code>[rep_effect id="${index}"]</code></span>
                </div>`;
            $('#rep-effect-images-container').append(html);
        });

        function updateShortcodeIndexes() {
            $('#rep-effect-images-container .rep-effect-image-block').each(function(index) {
                $(this).find('code').text('[rep_effect id="' + index + '"]');
            });
        }
    });
    </script>
    <?php
}


// ============================
// Sanitize Callback
// ============================
function rep_effect_sanitize_images($images) {
    if (!is_array($images)) return [];
    return array_values(array_filter(array_map('esc_url_raw', $images)));
}


// ============================
// Shortcode Handler
// ============================
function rep_effect_shortcode($atts) {
    $atts = shortcode_atts(['id' => 0], $atts);
    $images = get_option('rep_effect_images', []);
    if (!is_array($images)) $images = [];

    $id = intval($atts['id']);

    if (!isset($images[$id]) || empty($images[$id])) {
        return '<p>Rep Effect image not found.</p>';
    }

    $image_url = esc_url($images[$id]);

    if (is_admin()) return '';

    ob_start(); ?>
    <div class="rep-effect">
        <main>
            <section class="content-red">
                <div class="image"
                     data-repetition
                     data-repetition-count="10"
                     data-repetition-scale-interval="0.06"
                     style="background-image:url('<?php echo $image_url; ?>');">
                </div>
            </section>
        </main>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('rep_effect', 'rep_effect_shortcode');
