# Kilka Fork Packaging

This repository stores:

- the `kilka` theme
- the companion plugin `kilka-second-blog`
- the companion plugin `kilka-exhibitions`

Experimental exhibition work is currently developed on the
`feature/exhibitions` branch. The design draft is documented in
[`docs/exhibition-architecture.md`](docs/exhibition-architecture.md).

The exhibition content model lives in a separate first-party
`kilka-exhibitions` companion plugin. Version `0.2.0` adds a constrained block
editor: every exhibition contains one Sequence whose ordered children can only
be Image, Text, or Pause Spaces. The plugin is stored in this monorepo but
installed independently from the theme, following the existing Second Blog
packaging model. Image captions can be assigned to a visible caption, the
exhibition information panel, or accessible-only hidden output. The plugin
provides the ordered information-panel data; the Kilka theme owns the panel's
interactive presentation. Text Spaces provide constrained text scale, minimum
height, and vertical-placement presets without exposing arbitrary pixels or
arbitrary decorative styling; one optional short-line marker is available as a
restrained emphasis cue.

An optional public information heading, exhibition-level description, creator,
rights, and information-panel visibility are edited together in a dedicated
document-sidebar panel. The public heading is independent from the
administrative Exhibition title. The values remain portable plugin data while
the theme controls their public presentation.

The theme includes a dedicated single-Exhibition template. It renders the
plugin-owned Sequence inside the shared site header and footer without ordinary
post metadata, post navigation, comments, or blog sidebars.

## Attribution

The `kilka` theme is a maintained fork of the original Kilka theme by [Asha Themes](https://ashathemes.com/). Original theme copyright remains with Asha Themes. Elvira is the owner and maintainer of this fork.

## AI assistance

Development of this fork and its companion plugin was carried out with substantial assistance from OpenAI Codex and Google Gemini. These AI systems were used to generate and modify code, review changes, prepare documentation, and guide testing. Elvira directed the work, evaluated the results, and is responsible for published releases.

For deployment/publishing, build separate ZIP files.

## Build ZIP packages

```bash
./scripts/build-packages.sh
```

Output:

- `dist/kilka.zip`
- `dist/kilka-second-blog.zip`
- `dist/kilka-exhibitions.zip`

## Install order on WordPress

1. Upload and activate the optional companion plugins that the site needs:
   - `kilka-second-blog.zip`
   - `kilka-exhibitions.zip`
2. Upload and activate `kilka.zip` (theme).

This keeps the theme and portable content functionality separated. The theme
works without either companion plugin, and the plugins are independent of each
other.
