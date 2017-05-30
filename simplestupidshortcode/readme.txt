=== SimpleStupidShortcode ===
Contributors: lorenzck
Tags: shortcode, image, picture, embed, media
Requires at least: 4.4
Tested up to: 4.7.5
Stable tag: trunk
License: MIT
License URI: https://github.com/LorenzCK/SimpleStupidShortcode/blob/master/LICENSE

The simplest way to embed images into Wordpress posts.
No more absolute paths and fixed sizes for pictures in your posts: automatically embed responsive images using a simple shortcode.

== Description ==
The usual way to include images in Wordpress posts is to generate static anchor and ``  tags through the editor interface. However, these kind of links make your Wordpress site hard to migrate and make it especially hard to automatically change your theme (for instance enlarging the image size or the markup generated for pictures).

No longer: with this plug-in, you can use the simple `[image]` shortcode in any Wordpress post, in order to easily embed any uploaded image. The image is referenced by ID:
`[image id="123"]`
dynamically generates a correct `` tag, with responsive `srcset` support.

The shortcode supports the following parameters:
* `id`: the Wordpress ID of the attachment to include,
* `size`: size of the image to include (can be any standard size such as `thumbnail` or `full`, or any custom image size),
* `alt`: alternate description to include,
* `title`: image title to use in (optional) link,
* `link`: if set to true will also generate a link to the attachment.
