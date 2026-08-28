# Kilka Fork Packaging

This repository stores both:
- the `kilka` theme
- the companion plugin `kilka-second-blog`

Experimental exhibition work is currently developed on the
`feature/exhibitions` branch. The design draft is documented in
[`docs/exhibition-architecture.md`](docs/exhibition-architecture.md).

The proposed exhibition content model will live in a separate first-party
`kilka-exhibitions` companion plugin. It is not implemented or packaged yet.
The plugin will be stored in this monorepo but installed independently from the
theme, following the existing Second Blog packaging model.

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

## Install order on WordPress

1. Upload and activate `kilka-second-blog.zip` (plugin).
2. Upload and activate `kilka.zip` (theme).

This keeps theme and plugin separated (required for WordPress.org theme review when using CPT functionality).
