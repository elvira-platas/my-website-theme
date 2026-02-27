# Kilka Fork Packaging

This repository stores both:
- the `kilka` theme
- the companion plugin `kilka-second-blog`

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
