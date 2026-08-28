=== Kilka Exhibitions ===
Contributors: elvira-platas
Tags: exhibition, images, storytelling, custom post type
Requires at least: 5.7
Tested up to: 6.7
Requires PHP: 5.6
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Provides portable exhibition content and editor foundations for the Kilka ecosystem.

== Description ==

Kilka Exhibitions is an optional first-party companion plugin for the Kilka theme fork. The theme works without it, and the plugin does not depend on Kilka Second Blog.

Version 0.1.0 establishes the portable content foundation. It registers the Exhibition post type and exhibition-level metadata for creator information, copyright notices, the information panel, wall-tone intent, and search context.

The constrained Image, Text, and Pause space editor described in the project architecture is planned for a later development step.

== AI Assistance ==

Development of this plugin was carried out with substantial assistance from OpenAI Codex and Google Gemini. These AI systems were used to generate and modify code, review changes, prepare documentation, and guide testing. Elvira directed the work, evaluated the results, and is responsible for published releases.

== Installation ==

1. Upload the `kilka-exhibitions` folder to `/wp-content/plugins/` or install its ZIP from the Plugins screen.
2. Activate the plugin through the "Plugins" screen in WordPress.
3. Open `Exhibitions` in the WordPress administration menu.

== Frequently Asked Questions ==

= Is this plugin required by the Kilka theme? =

No. The Kilka theme works without this plugin. Install it only when exhibition content is needed.

= Does this plugin require Kilka Second Blog? =

No. The two plugins are independent.

= Will exhibition content stay if I switch themes? =

Yes. The Exhibition post type and its metadata are registered by this plugin, so the content remains available while the plugin is active.

= Does version 0.1.0 include the final exhibition editor? =

No. This first development version establishes the portable post type and metadata. The dedicated sequence editor is the next implementation stage.

== Changelog ==

= 0.1.0 =
* Adds the portable `kilka_exhibition` post type.
* Registers exhibition-level metadata for future editor controls.
* Provides independent activation and rewrite handling.

== Upgrade Notice ==

= 0.1.0 =
Initial development foundation. The dedicated sequence editor is not included yet.
