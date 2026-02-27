#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DIST_DIR="${ROOT_DIR}/dist"
BUILD_DIR="${ROOT_DIR}/.build"

THEME_SLUG="kilka"
PLUGIN_SLUG="kilka-second-blog"

THEME_STAGING_DIR="${BUILD_DIR}/${THEME_SLUG}"
PLUGIN_STAGING_DIR="${BUILD_DIR}/${PLUGIN_SLUG}"

if ! command -v rsync >/dev/null 2>&1; then
	echo "Error: rsync is required but not installed." >&2
	exit 1
fi

if ! command -v zip >/dev/null 2>&1; then
	echo "Error: zip is required but not installed." >&2
	exit 1
fi

if [ ! -d "${ROOT_DIR}/plugins/${PLUGIN_SLUG}" ]; then
	echo "Error: plugin directory not found: plugins/${PLUGIN_SLUG}" >&2
	exit 1
fi

rm -rf "${BUILD_DIR}"
mkdir -p "${THEME_STAGING_DIR}" "${PLUGIN_STAGING_DIR}" "${DIST_DIR}"

# Build theme package without repository-only and plugin files.
rsync -a \
	--exclude ".git/" \
	--exclude ".github/" \
	--exclude ".build/" \
	--exclude "dist/" \
	--exclude "plugins/" \
	--exclude "scripts/" \
	--exclude "AGENTS.md" \
	--exclude "README.md" \
	"${ROOT_DIR}/" "${THEME_STAGING_DIR}/"

# Build companion plugin package from its own directory.
rsync -a "${ROOT_DIR}/plugins/${PLUGIN_SLUG}/" "${PLUGIN_STAGING_DIR}/"

rm -f "${DIST_DIR}/${THEME_SLUG}.zip" "${DIST_DIR}/${PLUGIN_SLUG}.zip"

(
	cd "${BUILD_DIR}"
	zip -qr "${DIST_DIR}/${THEME_SLUG}.zip" "${THEME_SLUG}"
	zip -qr "${DIST_DIR}/${PLUGIN_SLUG}.zip" "${PLUGIN_SLUG}"
)

echo "Done."
echo "Theme package:  ${DIST_DIR}/${THEME_SLUG}.zip"
echo "Plugin package: ${DIST_DIR}/${PLUGIN_SLUG}.zip"
