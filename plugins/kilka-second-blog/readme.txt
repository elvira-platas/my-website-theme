=== Kilka Second Blog ===
Contributors: elvira-platas
Tags: custom post type, taxonomy, blog
Requires at least: 5.7
Tested up to: 6.7
Requires PHP: 5.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds the "Second Blog" content layer for the Kilka theme by registering a dedicated post type, taxonomies, and contextual helpers.

== Description ==

Kilka Second Blog is a companion plugin for the Kilka theme fork.

It registers:

* `world_note` custom post type (Second Blog)
* `world_note_category` taxonomy
* `world_note_tag` taxonomy

It also provides contextual helpers used by the theme:

* contextual search post type handling
* second-blog taxonomy query filtering
* term links with preserved post type context
* slug setting integration and rewrite rule maintenance

== Installation ==

1. Upload the `kilka-second-blog` folder to `/wp-content/plugins/` or install from the Plugins screen.
2. Activate the plugin through the "Plugins" screen in WordPress.
3. Go to `Settings -> Reading` and adjust the Second Blog slug if needed.

== Frequently Asked Questions ==

= Is this plugin required by the theme? =

The Kilka theme works without this plugin, but Second Blog features require it.

= Can I change the Second Blog URL slug? =

Yes. Use `Settings -> Reading` and update the "Second Blog URL Slug" field.

= Will content stay if I switch themes? =

Yes. The CPT and taxonomies are stored by this plugin, so content stays available.

== Changelog ==

= 1.0.0 =
* Initial public release as a companion plugin.
* Registers `world_note` CPT and related taxonomies.
* Adds contextual helper functions used by the Kilka theme.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
