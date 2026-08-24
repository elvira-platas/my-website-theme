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

if ! command -v rg >/dev/null 2>&1; then
	echo "Error: rg is required but not installed." >&2
	exit 1
fi

if ! command -v unzip >/dev/null 2>&1; then
	echo "Error: unzip is required but not installed." >&2
	exit 1
fi

if [ ! -d "${ROOT_DIR}/plugins/${PLUGIN_SLUG}" ]; then
	echo "Error: plugin directory not found: plugins/${PLUGIN_SLUG}" >&2
	exit 1
fi

# Reject legacy promo code and repository-only directories before packaging.
check_package_directory() {
	local package_dir="$1"
	local forbidden_dir
	local forbidden_path

	for forbidden_dir in welcome .agents .codex; do
		forbidden_path="$(find "${package_dir}" -type d -name "${forbidden_dir}" -print -quit)"
		if [ -n "${forbidden_path}" ]; then
			echo "Error: forbidden directory found in package: ${forbidden_path}" >&2
			return 1
		fi
	done

	if rg -n -i --hidden \
		--glob '*.php' --glob '*.js' --glob '*.css' --glob '*.txt' \
		'(wpashathemes|index\.php/cart|buy pro|view demo)' "${package_dir}"; then
		echo "Error: legacy promotional content found in package: ${package_dir}" >&2
		return 1
	fi
}

# Verify the final archive as well as the staging directory.
check_package_archive() {
	local archive="$1"
	local archive_file

	if unzip -Z1 "${archive}" | rg -n -i '(^|/)(welcome|\.agents|\.codex)(/|$)'; then
		echo "Error: forbidden directory found in archive: ${archive}" >&2
		return 1
	fi

	while IFS= read -r archive_file; do
		if unzip -p "${archive}" "${archive_file}" | rg -n -i '(wpashathemes|index\.php/cart|buy pro|view demo)'; then
			echo "Error: legacy promotional content found in archive: ${archive}" >&2
			return 1
		fi
	done < <(unzip -Z1 "${archive}" | rg '\.(php|js|css|txt)$')
}

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
	--exclude ".agents/" \
	--exclude ".codex/" \
	--exclude "welcome/" \
	--exclude "AGENTS.md" \
	--exclude "README.md" \
	"${ROOT_DIR}/" "${THEME_STAGING_DIR}/"

# Build companion plugin package from its own directory.
rsync -a "${ROOT_DIR}/plugins/${PLUGIN_SLUG}/" "${PLUGIN_STAGING_DIR}/"

check_package_directory "${THEME_STAGING_DIR}"
check_package_directory "${PLUGIN_STAGING_DIR}"

rm -f "${DIST_DIR}/${THEME_SLUG}.zip" "${DIST_DIR}/${PLUGIN_SLUG}.zip"

(
	cd "${BUILD_DIR}"
	zip -qr "${DIST_DIR}/${THEME_SLUG}.zip" "${THEME_SLUG}"
	zip -qr "${DIST_DIR}/${PLUGIN_SLUG}.zip" "${PLUGIN_SLUG}"
)

check_package_archive "${DIST_DIR}/${THEME_SLUG}.zip"
check_package_archive "${DIST_DIR}/${PLUGIN_SLUG}.zip"

echo "Done."
echo "Theme package:  ${DIST_DIR}/${THEME_SLUG}.zip"
echo "Plugin package: ${DIST_DIR}/${PLUGIN_SLUG}.zip"
