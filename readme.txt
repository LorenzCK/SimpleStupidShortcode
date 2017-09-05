=== Simple Stupid Shortcode ===
Contributors: lorenzck
Tags: shortcode, image, picture, embed, media
Requires at least: 4.6
Tested up to: 4.8.1
Stable tag: 1.3.0
License: MIT
License URI: https://github.com/LorenzCK/SimpleStupidShortcode/blob/master/LICENSE

The simplest and most flexible way to embed images into Wordpress posts. No more absolute paths or fixed sizes with a simple shortcode.

== Description ==

The usual way to include images in Wordpress posts is to generate static anchor and `<img>` tags through the content editor interface. However, these kind of links make your Wordpress site hard to migrate and make it especially hard to automatically change your output markup (for instance enlarging the image size or the markup generated for pictures).

No longer.
With this plug-in, you can use the simple `[image]` shortcode in any Wordpress post. Images are referenced by their ID, for instance:

`[image id="123"]`

This *shortcode* will generate the following HTML structure:

`
<div class="picture wp-image-123">
  <img src="path-to-123.jpg" srcset="&hellip;" />
</div>
`

You may easily add HTML classes or didascalies:

`[image id="123" didascaly="Nice picture." class="aligncenter"]`

This generates the following HTML block, which can be easily styled with CSS:

`
<div class="picture wp-image-123 aligncenter">
  <img src="path-to-123.jpg" srcset="&hellip;" />
  <div class="didascaly">Nice picture.</div>
</div>
`

The *shortcode* supports the following parameters:

* `id`: the Wordpress ID of the attachment to include,
* `size`: size of the image to include (can be any standard size such as `thumbnail` or `full`, or any custom image size),
* `alt`: alternate description to include in the `&lt;img&gt;` tag,
* `title`: image title to use in (optional) link,
* `link`: if set to true will also generate a link to the attachment,
* `didascaly`: text to add as a didascaly,
* `class`: additional HTML/CSS classes to add to the root element.

== Screenshots ==

1. *Shortcode* generation button with file picker.

== Changelog ==

= 1.3 =
* Added localization support.
* Updated description and help.

= 1.2 =
* Added “add media” button with file picker on content editor.

= 1.1 =
* Added `didascaly` and `class` parameters.

= 1.0 =
* Initial release.
