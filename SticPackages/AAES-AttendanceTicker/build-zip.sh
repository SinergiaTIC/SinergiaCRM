#!/usr/bin/env bash
# STIC-Custom AAES - 20261109 - Build script for the AAES Attendance Ticker package (IMPL-AAES-001, Fase D)
# Stages the package tree into a temp dir (manifest + install docs + the ticker
# files taken from the repo working tree) and zips it as a Module Loader package.
# Usage (from anywhere): SticPackages/AAES-AttendanceTicker/build-zip.sh
# Output: SticPackages/AAES-AttendanceTicker/dist/AAES-AttendanceTicker-v<version>.zip
# END STIC-Custom AAES
set -euo pipefail

PKG_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "$PKG_DIR/../.." && pwd)"
STAGE="$(mktemp -d "${TMPDIR:-/tmp}/AAES-packager.XXXXXX")"
trap 'rm -rf "$STAGE"' EXIT

VERSION=$(php -r 'require $argv[1]; echo $manifest["version"];' "$PKG_DIR/manifest.php")

# --- sanity: syntax-check every ticker file before packaging
find "$ROOT/custom/include/AAES" "$ROOT/custom/Extension/application/Ext/Language" \
     "$ROOT/custom/Extension/modules/stic_Attendances/Ext" \
     -name "*AAES_AttendanceTicker*.php" -print0 | while IFS= read -r -d '' f; do
    php -l "$f" >/dev/null
done

# --- package root: manifest + install docs
cp "$PKG_DIR/manifest.php" "$STAGE/"
cp -r "$PKG_DIR/install" "$STAGE/install"

# --- ticker files exactly as they must land on an instance
mkdir -p "$STAGE/custom/include/AAES"
cp -r "$ROOT/custom/include/AAES/AttendanceTicker" "$STAGE/custom/include/AAES/"

mkdir -p "$STAGE/custom/Extension/application/Ext/Language" \
         "$STAGE/custom/Extension/modules/stic_Attendances/Ext/ActionFileMap" \
         "$STAGE/custom/Extension/modules/stic_Attendances/Ext/Menus" \
         "$STAGE/custom/Extension/modules/stic_Attendances/Ext/Language"
cp "$ROOT"/custom/Extension/application/Ext/Language/*AAES_AttendanceTicker.php \
   "$STAGE/custom/Extension/application/Ext/Language/"
cp "$ROOT"/custom/Extension/modules/stic_Attendances/Ext/ActionFileMap/AAES_AttendanceTicker.php \
   "$STAGE/custom/Extension/modules/stic_Attendances/Ext/ActionFileMap/"
cp "$ROOT"/custom/Extension/modules/stic_Attendances/Ext/Menus/AAES_AttendanceTicker.php \
   "$STAGE/custom/Extension/modules/stic_Attendances/Ext/Menus/"
cp "$ROOT"/custom/Extension/modules/stic_Attendances/Ext/Language/*AAES_AttendanceTicker.php \
   "$STAGE/custom/Extension/modules/stic_Attendances/Ext/Language/"

# --- staged inventory (control check: manifest + install docs + 16 php files)
find "$STAGE" -type f | sort

function make_zip()
{
    # Zip the staged tree into $1 (zip CLI when available, python3 fallback)
    local target="$1"
    if command -v zip >/dev/null 2>&1; then
        (cd "$STAGE" && zip -rq "$target" .)
    else
        python3 - "$STAGE" "$target" <<'PYEOF'
import os, sys, zipfile
stage, target = sys.argv[1], sys.argv[2]
with zipfile.ZipFile(target, "w", zipfile.ZIP_DEFLATED) as zf:
    for root, dirs, files in os.walk(stage):
        dirs.sort()
        for name in sorted(files):
            full = os.path.join(root, name)
            zf.write(full, os.path.relpath(full, stage))
PYEOF
    fi
}

ZIP_FILE="$PKG_DIR/dist/AAES-AttendanceTicker-v${VERSION}.zip"
rm -f "$ZIP_FILE"
make_zip "$ZIP_FILE"

# --- integrity hashes (share these with the entity: the Module Loader needs
# the EXACT file; re-compressing or transferring it as a text file breaks it)
echo "md5:    $(md5sum "$ZIP_FILE" | cut -d' ' -f1)  $ZIP_FILE"
echo "sha256: $(sha256sum "$ZIP_FILE" | cut -d' ' -f1)  $ZIP_FILE"
echo "zip: python3 -m zipfile -t"
python3 -m zipfile -t "$ZIP_FILE" && echo "zip integrity: OK"
echo "OK: $ZIP_FILE"
