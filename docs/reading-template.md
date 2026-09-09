# Reading template

The optional **Reading** template presents an ordinary WordPress Page as a
continuous document. It uses the shared site header, menu, color scheme and
footer, with a bounded text column and no blog metadata, sidebar or comments.
No reader-specific JavaScript, cookies, storage or background requests are
introduced.

## Editing

1. Create a Page and choose the **Reading** template.
2. Use the page title for the document title; do not repeat it as another H1.
3. Insert the **Reading document** pattern from the Text patterns category.
4. Replace the byline with the author name, then put the document in the
   **Reading text** group. Use ordinary paragraphs, headings, images and
   separators in their reading order. Add images from the local Media Library
   and supply appropriate alt text and optional captions.
5. Edit or remove the final **Reading note** group. It can contain a source
   link or a rights statement. Remove all pattern placeholder text before
   publishing.

The pattern is a starting point, not a locked template. Existing page content
also works without the pattern. The author is explicit content, not inferred
from the WordPress account that edited the page. Theme styles identify the
byline, story and optional note; all three remain ordinary readable core-block
HTML when switching themes.

## Scope and boundaries

- One canonical `post_content`, without a duplicate text file in the theme.
- The template controls presentation; it introduces no post type, custom
  database table, content metadata or new installable package.
- No generated page numbers or fixed-height text boxes. Long text can grow,
  browser zoom can reflow it, and the document remains readable without JS.
- Existing manually inserted page breaks retain WordPress page links. There
  is no automatic pagination or download generator in this first pass.
- Shared site color preference behavior is unchanged: an explicit selection
  can be stored locally under `kilka-color-scheme`. Reading position and
  reader activity are neither stored nor transmitted.
- Emoji CDN fallback and the core embedded-content messaging script are
  disabled on Reading pages. Use locally hosted media and ordinary source
  links, not third-party embeds or tracking HTML. A theme cannot guarantee
  the behavior of separately installed plugins or server configuration.
- An initial privacy check must use an anonymous browser context and inspect
  actual requests, cookies and storage, including interactions. Administrative
  WordPress login is separate from the public reading experience.

Reader assets are loaded only for the page template. Editor styles are scoped
to the three document block styles so unrelated content retains its typography.
