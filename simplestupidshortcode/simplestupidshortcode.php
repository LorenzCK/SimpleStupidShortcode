<?php
/*
Plugin Name: Simple Stupid Shortcode
Plugin URI: https://github.com/LorenzCK/SimpleStupidShortcode
Description: The simplest way to embed images into Wordpress posts.
Author: Lorenz Cuno Klopfenstein
Version: 1.0.0
Author URI: https://github.com/LorenzCK
*/

// Requires Wordpress 4.4 for wp_get_attachment_image_srcset()

function sss_shortcode_handler($atts, $content = NULL) {
    $a = shortcode_atts(array(
        'id' => '',
        'size' => 'medium',
        'alt' => '',
        'title' => '',
        'link' => false
    ), $atts, 'image');
    
    if(!$a['id']) {
        return '';
    }
    
    $img_data = wp_get_attachment_image_src($a['id'], $a['size'], false);
    if($img_data == false) {
        return '';
    }

    $ret = '';
    if($a['link']) {
        $ret .= '<a href="' . wp_get_attachment_url($a['id']) . '">';
    }
    $ret .= '<img src="' . $img_data[0] . '" width="' . $img_data[1] . '" height="' . $img_data[2] . '" alt="" srcset="' . wp_get_attachment_image_srcset($a['id'], $a['size']) . '" class="size-' . $a['size'] . ' wp-image-' . $a['id'] . '" />';
    if($a['link']) {
        $ret .= '</a>';
    }

    return $ret;
}

add_shortcode('image', 'sss_shortcode_handler');
