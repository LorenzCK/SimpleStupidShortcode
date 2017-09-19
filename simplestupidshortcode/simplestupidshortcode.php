<?php
/*
Plugin Name: Simple Stupid Shortcode
Plugin URI: https://github.com/LorenzCK/SimpleStupidShortcode
Description: The simplest and most flexible way to embed images or put links into Wordpress posts.
Author: Lorenz Cuno Klopfenstein
Version: 1.5.0
Author URI: https://github.com/LorenzCK
Text domain: simple-stupid-shortcode
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

/* [image] SHORTCODE */

function sss_image_shortcode_handler($atts, $content = null) {
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
    $display_link = filter_var($a['link'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $target_link = ($display_link === null) ? $a['link'] : wp_get_attachment_url($a['id']);

    // Auto-set alt and title attributes with didascaly, if set
    if(!$a['alt'] && $a['didascaly']) {
        $a['alt'] = $a['didascaly'];
    }
    if(!$a['title'] && $a['didascaly']) {
        $a['title'] = $a['didascaly'];
    }

    // Auto-set alt with media metadata
    if(!$a['alt']) {
        // Alt-text extraction from wp_get_attachment_image()
        // https://core.trac.wordpress.org/browser/tags/4.8/src/wp-includes/media.php#L885
        $a['alt'] = trim(strip_tags(get_post_meta($a['id'], '_wp_attachment_image_alt', true)));
    }

    $img_data = wp_get_attachment_image_src($a['id'], $a['size'], false);
    if($img_data == false) {
        return '';
    }

    $ret = '<div class="picture wp-image-' . $a['id'] . ' size-' . $a['size'];
    if($a['class']) {
        $ret .= ' ' . esc_attr($a['class']);
    }
    $ret .= '">';

    // Display links if 'link' is not boolean (URL) or if set to true
    if($display_link === null || $display_link) {
        $ret .= '<a href="' . esc_attr($target_link) . '"';
        if($a['title']) {
            $ret .= ' title="' . esc_attr($a['title']) . '"';
        }
        $ret .= '>';
    }

    $ret .= '<img src="' . $img_data[0] . '" width="' . $img_data[1] . '" height="' . $img_data[2] . '"';

    if(function_exists('wp_get_attachment_image_srcset')) {
        $ret .= 'srcset="' . wp_get_attachment_image_srcset($a['id'], $a['size']) . '"';
    }

    if($a['alt']) {
        $ret .= 'alt="' . esc_attr($a['alt']) . '"';
    }
    $ret .= '/>';

    if($display_link === null || $display_link) {
        $ret .= '</a>';
    }

    if($a['didascaly']) {
        $ret .= '<div class="didascaly">' . $a['didascaly'] . '</div>';
    }

    $ret .= '</div>';

    return $ret;
}
add_shortcode('image', 'sss_image_shortcode_handler');

/* [page] & [link] SHORTCODES */

function sss_core_get_target($href, $type) {
    if(is_int($href)) {
        // Resolve via ID
        return get_post($href);
    }
    else if(strpos($href, '/') !== false) {
        // Must be a path
        return get_page_by_path($href, OBJECT, $type);
    }
    else {
        // Default to search by slug
        $pages = get_posts(array(
            'name'        => $href,
            'post_type'   => $type,
            'numberposts' => 1
        ));

        if($pages && count($pages) >= 1) {
            return $pages[0];
        }
        else {
            return null;
        }
    }
}

function sss_core_link_shortcode($atts, $content = null) {
    if(!$atts['href']) {
        return $content;
    }

    $target = sss_core_get_target($atts['href'], $atts['type']);
    if(!$target) {
        return $content;
    }

    // Auto set title to target title and content
    if(!$atts['title']) {
        $atts['title'] = get_the_title($target);
    }
    if(!$content) {
        $content = get_the_title($target);
    }

    $html = '<a href="' . esc_url(get_permalink($target)) . '" title="' . esc_attr($atts['title']) . '" class="link-' . $target->ID . ' link-' . $target->post_type . ' link-' . $target->post_status . ' link-author-' . $target->post_author;
    if($atts['class']) {
        $html .= ' ' . esc_attr($atts['class']);
    }
    $html .= '">' . $content . '</a>';

    return $html;
}

function sss_page_shortcode_handler($atts, $content = null) {
    $a = shortcode_atts(array(
        'href' => '',
        'title' => '',
        'class' => null
    ), $atts, 'page');

    $a['type'] = 'page';

    return sss_core_link_shortcode($a, $content);
}
add_shortcode('page', 'sss_page_shortcode_handler');

function sss_link_shortcode_handler($atts, $content = null) {
    $a = shortcode_atts(array(
        'href' => '',
        'title' => '',
        'class' => null,
        'type' => 'post'
    ), $atts, 'link');

    return sss_core_link_shortcode($a, $content);
}
add_shortcode('link', 'sss_link_shortcode_handler');

/* ADMIN PANEL */

add_action('media_buttons', 'sss_add_media_button', 12);

function sss_add_media_button() {
    ?>
    <button type="button" id="sss-insert-image-button" class="button thickbox add_media" data-editor="content">
        <span class="wp-media-buttons-icon"></span>
        <?php _e('Add [image]', 'simple-stupid-shortcode'); ?>
    </button>
    <?php
}

add_action('wp_enqueue_media', 'sss_add_media_button_include_js');

function sss_add_media_button_include_js() {
    wp_enqueue_script('media_button', plugins_url('simplestupidshortcode-admin.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('media_button', 'ssst', array(
        'title_insert' => __('Pick a picture', 'simple-stupid-shortcode'),
        'label_insert' => __('Insert', 'simple-stupid-shortcode')
    ));
}
