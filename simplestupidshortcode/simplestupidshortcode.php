<?php
/*
Plugin Name: Simple Stupid Shortcode
Plugin URI: https://github.com/LorenzCK/SimpleStupidShortcode
Description: The simplest and most flexible way to embed images into Wordpress posts.
Author: Lorenz Cuno Klopfenstein
Version: 1.3.0
Author URI: https://github.com/LorenzCK
Text domain: simplestupidshortcode
License: MIT

MIT License

Copyright (c) 2017 Lorenz Cuno Klopfenstein

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
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
        <?php _e('Add [image]', 'simplestupidshortcode'); ?>
    </button>
    <?php
}

add_action('wp_enqueue_media', 'sss_add_media_button_include_js');

function sss_add_media_button_include_js() {
    wp_enqueue_script('media_button', plugins_url('simplestupidshortcode-admin.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('media_button', 'ssst', array(
        'title_insert' => __('Pick a picture', 'simplestupidshortcode'),
        'label_insert' => __('Insert', 'simplestupidshortcode')
    ));
}
