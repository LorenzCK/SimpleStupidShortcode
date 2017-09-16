=== Simple Stupid Shortcode ===
Contributors: lorenzck
Tags: shortcode, image, picture, embed, media
Requires at least: 4.6
Tested up to: 4.8.1
Stable tag: trunk
License: MIT
License URI: https://github.com/LorenzCK/SimpleStupidShortcode/blob/master/LICENSE

The simplest and most flexible way to embed images or generate links in Wordpress. Shortcodes to finally ditch all absolute paths and fixed sizes.

== Description ==

The usual way to include images in Wordpress posts is to generate static anchor and `<img>` tags through the content editor interface. Similarly, links to other posts or pages of your blog are also generated as absolute `<a>` tags by the content editor.
These kind of fixed links make your Wordpress site hard to migrate and make it especially hard to automatically change your output markup (for instance if you decide to change the image size or change a post’s title).

No longer.
With this plug-in, you can use the simple `[image]`, `[link]`, and `[page]` shortcodes in any of your posts.

= Image shortcode =

Images are referenced by their ID, for instance:

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

* `id`: the Wordpress ID of the attachment to include;
* `size`: size of the image to include (can be any standard size such as `thumbnail` or `full`, or any custom image size);
* `alt`: alternate description to include in the `&lt;img&gt;` tag;
* `title`: image title to use in (optional) link;
* `link`: if set to `true`, `1`, `yes`, or an equivalent value, a link to the full-resolution attachment will be generated, otherwise a fully specified target URL can be specified;
* `didascaly`: text to add as a didascaly;
* `class`: additional HTML/CSS classes to add to the root element.

= Page and link shortcodes =

This couple of *shortcodes* automatically generate hypertext links to pages or posts of your Wordpress installation. Target posts can be referenced by ID, by *slug*, or by *path* (part of the posts’s URL, including slashes).

Thus, `[link href="123"]…[/link]`, `[link href="slug-of-post"]…[/link]`, and `[link href="subpage/other/slug-of-post"]…[/link]` are all equivalent.

These *shortcodes* are “enclosing” (they wrap the content of the link, just like HTML tags) and they support the following parameters:

* `href`: the ID, *slug*, or *path* of the target post or page;
* `title`: the title to use in the link, if not set the target post’s title will be used;
* `class`: additional HTML/CSS classes to add to the link;
* `type`: type of the target post (defaults to `page` for `[page]` and `post` for `[link]`).

If no link content to be enclosed is provided, the posts’s title is automatically used as link text.

== Screenshots ==

1. *Shortcode* generation button with file picker.

== Changelog ==

= 1.5 =
* Add support for URLs in `link` attributes for [image] shortcodes.

= 1.4 =
* Add [page] and [link] shortcodes.

= 1.3 =
* Added localization support.
* Updated description and help.

= 1.2 =
* Added “add media” button with file picker on content editor.

= 1.1 =
* Added `didascaly` and `class` parameters.

= 1.0 =
* Initial release.
