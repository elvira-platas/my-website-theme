# Exhibition Architecture Draft

Status: design draft. This document describes the proposed content model and
editing workflow. It does not define the final visual design.

## Purpose

Kilka exhibitions are small, deliberately composed sequences of images, text,
and empty intervals. They are not automatic media-library galleries, portfolio
grids, or collections of every image used in posts.

The first prototype is based on three related ideas:

- `ma`: an interval is an active part of the composition;
- tokonoma: one object can hold a space without competing with a collection;
- emaki: a sequence is revealed gradually and the visitor controls its pace.

These ideas guide the rhythm of the interface. They do not prescribe Japanese
visual decoration.

## Ownership of Responsibilities

### First-party exhibition plugin

A future `Kilka Exhibitions` companion plugin should own all content that must
survive a theme change:

- the Exhibition post type;
- exhibition metadata;
- the ordered sequence of spaces;
- editor controls and validation;
- neutral semantic fallback markup;
- REST registration needed by the editor.

This should be a separate first-party plugin, not part of `Kilka Second Blog`
and not a dependency on a third-party gallery plugin.

### Theme

The theme should own presentation:

- the shared site frame;
- exhibition spacing and responsive layout;
- typography and wall colours;
- image edge treatment such as shadows or frames;
- the information panel;
- the common footer.

If the theme changes while the plugin remains active, exhibition content must
remain editable and receive usable neutral fallback output.

## Repository and Installation Model

Exhibition development remains in the same GitHub monorepo as the theme and
Second Blog. A separate repository is unnecessary while the packages share one
maintainer, release process, and theme integration.

The installable products remain independent:

- `kilka.zip`: theme presentation;
- `kilka-second-blog.zip`: Second Blog content model;
- `kilka-exhibitions.zip`: exhibition content model and editor, once built.

The theme must work without either plugin. Each plugin must be installable and
activatable independently. The exhibition plugin must not depend on the Second
Blog plugin.

Experimental work is isolated on the `feature/exhibitions` branch until the
content model, editor, fallback rendering, and theme integration are ready for
review. The stable `main` branch remains release-oriented.

## Shared Site Frame

The site frame is common to posts, pages, blog archives, Second Blog, and
exhibitions. It contains:

- the site name linked to the main page;
- the primary navigation control;
- shared focus and accessibility behaviour;
- the site footer.

On desktop, the site name may move from a large centred header to a quiet
position at the edge of the page. The navigation can occupy the opposite edge.
This direction must be prototyped on normal blog pages before it becomes the
default theme layout.

On mobile, the site name and menu return to one compact horizontal row.

An exhibition may suppress the normal blog masthead and content container, but
it must not create a separate brand identity.

## Footer

The same semantic footer and the same configured links should be available on
every public view.

Within an exhibition, the footer appears only after the last space and its
final interval. It acts as the exit from the exhibition and must not be fixed
over images or inserted between works.

The markup and content remain shared with the rest of the site. Contextual
exhibition styles may make its background blend with the exhibition wall, but
the exhibition must not maintain a second set of footer settings.

## Content Model

### Exhibition

Recommended WordPress post type: `kilka_exhibition`.

Core fields:

- title;
- slug and permalink;
- publication status;
- excerpt used as the curatorial or introductory note;
- optional featured image for links and previews;
- ordered sequence of spaces.

Exhibition-level metadata:

- default creator name;
- default copyright notice;
- information-panel visibility;
- wall-tone preset;
- optional search/social summary if the excerpt is not sufficient.

The first version should support multiple exhibitions even if the live site
initially publishes only one.

### Sequence

The sequence should be edited with a dedicated, constrained WordPress block
rather than a core Gallery block. The sequence contains only supported Space
blocks and provides a simple add/reorder workflow.

Proposed first-version space types:

1. Image Space
2. Text Space
3. Pause Space

A pair, diptych, audio, or video space can be considered later. They should not
be included until a real exhibition requires them.

### Image Space

Required data:

- media-library attachment;
- accessible description, normally inherited from attachment alt text;
- order within the exhibition.

Curatorial controls:

- scale: `small`, `medium`, `large`, or `immersive`;
- alignment: `left`, `centre`, or `right`;
- interval after the image: `short`, `normal`, or `long`;
- caption mode: `information panel`, `visible`, or `hidden`;
- optional creator and copyright overrides.

The editor must not expose pixel sizes, arbitrary CSS classes, per-image wall
colours, shadows, frames, animations, or desktop/mobile duplicates in the first
version. Those choices belong to the shared visual system.

### Text Space

A Text Space supports a short curatorial passage, quotation, transition, or
section marker without turning the exhibition into a normal article.

Initial controls:

- text;
- width: `narrow` or `standard`;
- alignment: `left` or `centre`;
- interval after the text.

Long essays should remain normal pages or posts and may be linked from the
information panel.

### Pause Space

A Pause Space contains no visible content. It creates an intentional interval
that can be perceived independently of the work before it.

Initial lengths:

- `short`;
- `normal`;
- `long`;
- `full viewport`.

## Editing Experience

The editor should feel like arranging an exhibition plan, not configuring a
gallery plugin.

Recommended workflow:

1. Create an Exhibition.
2. Enter its title, slug, introductory note, and default rights information.
3. Select **Add space**.
4. Choose Image, Text, or Pause.
5. Select an image through the standard WordPress media library when needed.
6. Choose from the limited curatorial presets.
7. Reorder spaces with drag-and-drop and WordPress List View controls.
8. Preview the full sequence on desktop and mobile.
9. Publish the exhibition and add it to navigation only when approved.

The editor should automatically insert one Sequence container and restrict its
children to supported Space blocks. It should not require authors to remember
custom classes or edit serialized metadata manually.

## Information and Search Context

Visual silence must not remove semantic information. Published output should
include:

- one page-level heading;
- meaningful image alt text;
- captions or descriptions available in the information panel;
- semantic figures and captions where appropriate;
- structured `CollectionPage` and `ImageObject` data;
- explicit creator and copyright data only when supplied by the author;
- a useful excerpt and normal WordPress document metadata.

Text hidden only visually for accessibility remains available to assistive
technology. Search text must not be fabricated or repeated merely to attract
traffic.

## Responsive Rules

Authors choose curatorial intent, not a separate mobile composition.

The theme translates the same sequence automatically:

- desktop preserves left/right placement and greater variation in scale;
- mobile centres most works and reduces extreme scale differences;
- intervals remain meaningful but become shorter on small screens;
- the footer follows the final interval on every viewport;
- the information panel remains keyboard- and touch-accessible.

Mobile-specific overrides should be added only after real examples prove that
automatic translation is insufficient.

## Deliberately Deferred Visual Decisions

The following details remain experimental until the editing model is proven:

- shadows versus frames;
- exact wall colours;
- precise spacing values;
- transitions and motion;
- the final position of the site name;
- the relationship between the common footer and the exhibition wall;
- visible caption typography.

## Proposed Implementation Order

1. Approve the content model and editor controls.
2. Scaffold the separate first-party exhibition plugin and post type.
3. Build the constrained Sequence and Space editor blocks.
4. Render neutral, accessible fallback markup from the plugin.
5. Connect the current theme prototype to the new data model.
6. Restore the shared footer at the end of the exhibition.
7. Prototype the edge-positioned site identity on blog, single-post, and
   exhibition views.
8. Finalise visual treatments only after the complete workflow is usable.

## Questions to Resolve Before Implementation

1. Should visible captions be exceptional or common?
2. Does the first release need Text Spaces, or only Image and Pause Spaces?
3. Should an exhibition have a normal archive page immediately, or remain
   accessible only through deliberate menu links?
4. Should the information panel list every work, or only exhibition-level
   context and rights information?
5. Should the common footer keep exactly the same surface colour everywhere,
   or adapt its background while preserving the same markup and content?
