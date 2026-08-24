# Kilka Fork Packaging

This repository stores both:
- the `kilka` theme
- the companion plugin `kilka-second-blog`

## Attribution

The `kilka` theme is a maintained fork of the original Kilka theme by [Asha Themes](https://ashathemes.com/). Original theme copyright remains with Asha Themes; fork maintenance and custom modifications are by Elvira.

## AI assistance

This repository has been developed and maintained by Elvira with assistance from OpenAI Codex and Google Gemini, AI tools used for code review, implementation, documentation, and testing guidance. Their assistance was used under Elvira's direction. Project decisions, review, and responsibility for the published code remain with Elvira.

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
