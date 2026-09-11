=== Kilka Reader ===
Contributors: elvira-platas
Tags: reading, accessibility, pages
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Portable reading pages with text size controls and a return to a related publication.

== Description ==

Use ordinary WordPress Pages with the Reading template. Content stays in core blocks. Existing Kilka Reading pages keep their content and URLs. A supporting theme controls presentation; other themes receive a neutral standalone reading page. This plugin does not require the Kilka theme or other Kilka plugins.

Choose a related published blog post in the page editor's Reader panel. A Close reader icon links to the related publication, or the site home page if no public target is available. Private, draft and password-protected targets are hidden. Optional JavaScript controls adjust text size from 80 to 160 percent and reset it. Align left is the default; readers may choose Justify with browser-native automatic hyphenation. Set the optional Text language field for correct pronunciation and language-specific hyphenation. Alignment also lasts only while the page is open. Three reading palettes are available: Cream (initial), Light and Graphite. The reader palette does not change the site color preference and resets to Cream on reload. The settings are grouped in a collapsible panel; Close reader and Reading settings remain visible during reading. The Kilka template and neutral fallback omit the site header and footer. Without JavaScript, the complete document and exit remain usable.

No analytics, telemetry, cookies, browser storage or background requests are added. Text size lasts only while the page is open. Separate plugins, embedded content and server logs are outside this plugin's control. Use locally hosted media for a reading experience without third-party requests.

Deactivation preserves content and metadata. No automatic pagination or EPUB generation is included in version 0.1.0.

== Installation ==

1. Install and activate the plugin ZIP.
2. Create or edit a Page and choose the Reading template.
3. Insert the Reading document pattern or keep existing core-block content.
4. Optionally choose a related publication in the Reader panel and save.

== AI Assistance ==

OpenAI Codex substantially assisted code generation, review, documentation and testing. Elvira directed development and is responsible for published releases.

== Changelog ==

= 0.1.0 =
* Preserve existing Reading pages and offer a neutral theme fallback.
* Add a protected editor field for the related publication.
* Add optional text size controls without storing reader activity.
