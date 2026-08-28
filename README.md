# Kilka Fork Packaging

This repository stores:

- the `kilka` theme
- the companion plugin `kilka-second-blog`
- the companion plugin `kilka-exhibitions`

Experimental exhibition work is currently developed on the
`feature/exhibitions` branch. The design draft is documented in
[`docs/exhibition-architecture.md`](docs/exhibition-architecture.md).

The exhibition content model lives in a separate first-party
`kilka-exhibitions` companion plugin. Version `0.1.0` establishes the portable
post type and metadata foundation; the dedicated sequence editor is not yet
implemented. The plugin is stored in this monorepo but installed independently
from the theme, following the existing Second Blog packaging model.

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
