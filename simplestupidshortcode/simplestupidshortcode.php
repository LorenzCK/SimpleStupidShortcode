<?php
/*
Plugin Name: Simple Stupid Shortcode
Plugin URI: https://github.com/LorenzCK/SimpleStupidShortcode
Description: The simplest way to embed images into Wordpress posts.
Author: Lorenz Cuno Klopfenstein
Version: 1.2.0
Author URI: https://github.com/LorenzCK
*/

function sss_shortcode_handler($atts, $content = NULL) {
    $a = shortcode_atts(array(
        'id' => '',
        'size' => 'medium',
        'alt' => '',
        'title' => '',
        'link' => true,
        'didascaly' => '',
        'class' => null
    ), $atts, 'image');

    if(!$a['id']) {
        return '';
    }

    // Extract link to boolean
    $display_link = filter_var($a['link'], FILTER_VALIDATE_BOOLEAN);

    // Auto-set alt and title attributes with didascaly, if set
    if(!$a['alt'] && $a['didascaly']) {
        $a['alt'] = $a['didascaly'];
    }
    if(!$a['title'] && $a['didascaly']) {
        $a['title'] = $a['didascaly'];
    }

    $img_data = wp_get_attachment_image_src($a['id'], $a['size'], false);
    if($img_data == false) {
        return '';
    }

    $ret = '<div class="picture wp-image-' . $a['id'] . ' size-' . $a['size'];
    if($a['class']) {
        $ret .= ' ' . $a['class'];
    }
    $ret .= '">';

    if($display_link) {
        $ret .= '<a href="' . wp_get_attachment_url($a['id']) . '"';
        if($a['title']) {
            $ret .= ' title="' . $a['title'] . '"';
        }
        $ret .= '>';
    }

    $ret .= '<img src="' . $img_data[0] . '" width="' . $img_data[1] . '" height="' . $img_data[2] . '"';

    if(function_exists('wp_get_attachment_image_srcset')) {
        $ret .= 'srcset="' . wp_get_attachment_image_srcset($a['id'], $a['size']) . '"';
    }

    if($a['alt']) {
        $ret .= 'alt="' . $a['alt'] . '"';
    }
    $ret .= '/>';

    if($display_link) {
        $ret .= '</a>';
    }

    if($a['didascaly']) {
        $ret .= '<div class="didascaly">' . $a['didascaly'] . '</div>';
    }

    $ret .= '</div>';

    return $ret;
}

add_shortcode('image', 'sss_shortcode_handler');

/* ADMIN PANEL */

add_action('media_buttons', 'sss_add_media_button', 12);

function sss_add_media_button() {
    ?>
    <button type="button" id="sss-insert-image-button" class="button thickbox add_media" data-editor="content">
        <span class="wp-media-buttons-icon"></span>
        <?php echo _('Add'); ?> [image]
    </button>
    <?php
}

add_action('wp_enqueue_media', 'sss_add_media_button_include_js');

function sss_add_media_button_include_js() {
    wp_enqueue_script('media_button', plugins_url('simplestupidshortcode-admin.js', __FILE__), array('jquery'), '1.0', true);
}
