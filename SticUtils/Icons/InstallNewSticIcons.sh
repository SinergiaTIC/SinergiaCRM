#!/bin/bash

# Script for rebuilding SinergiaCRM icon fonts and stylesheets

# This script is used to rebuild the SinergiaCRM icon fonts and stylesheets. 
# It is adapted from a script found at https://github.com/salesagility/SuiteP-Icon-Font. 
# This script should be run without parameters from the SticUtils/Icons folder.

# Dependencies:
# - git for cloning the SuiteP-Icon-Font repository
# - svgo for compressing and copying SticIcons to the tmp folder
# - icon-font-generator for creating the icon font from the SVG files
# - scssphp for compiling the SCSS sources into CSS

# Instructions:
# 1. Ensure that all dependencies are installed and available in the PATH.
# 2. Navigate to the SticUtils/Icons folder and run bash InstallNewIcons.sh

# Usage of SuiteP-Icon-Font repository:
# The SuiteP-Icon-Font repository (https://github.com/salesagility/SuiteP-Icon-Font) is used 
# to obtain the icon files required for the SinergiaCRM icon fonts and stylesheets.
# The repository is cloned to the SuiteP-Icon-Font which is deleted at the end of the script

# Adding a new icon:
# To add a new icon to SinergiaCRM, copy it to the SticUtils/Icons/SticIcons folder. 
# It is a good idea to start with a copy of one of the existing icons, rename it, modify it with Inkscape, 
# and save it as a plain SVG file.



# Remove any existing tmpSrc directory and create a new one
rm -rf tmpSrc
mkdir -p tmpSrc

# Clone the SuiteP-Icon-Font repository to get the icon files
rm -rf SuiteP-Icon-Font/
git clone https://github.com/salesagility/SuiteP-Icon-Font

# Copy SuiteP Icons to tmp folder
cp SuiteP-Icon-Font/src/* tmpSrc

# Compress and copy SticIcons to tmp folder
svgo -f SticIcons/ -o tmpSrc

# Create font from ./src folder
icon-font-generator tmpSrc/*svg -o ../../themes/SuiteP/css/suitep-base/ --mono --center -p suitepicon --csspath ../../themes/SuiteP/css/suitep-base/suitepicon-glyphs.scss --name suitepicon

# Regenerate style.css from scss sources to activate new suitepicon classes
echo "Generating css for Stic subtheme"
../../SticInclude/vendor/scssphp/bin/pscss -s compressed ../../themes/SuiteP/css/Stic/style.scss > ../../themes/SuiteP/css/Stic/style.css && cp ../../themes/SuiteP/css/Stic/style.css ../../cache/themes/SuiteP/css/Stic/style.css

echo "Generating css for SticCustom subtheme"
../../SticInclude/vendor/scssphp/bin/pscss -s compressed ../../themes/SuiteP/css/SticCustom/style.scss > ../../themes/SuiteP/css/SticCustom/style.css && cp ../../themes/SuiteP/css/SticCustom/style.css ../../cache/themes/SuiteP/css/SticCustom/style.css

echo "Generating css for Dawn subtheme"
../../SticInclude/vendor/scssphp/bin/pscss -s compressed ../../themes/SuiteP/css/Dawn/style.scss > ../../themes/SuiteP/css/Dawn/style.css && mkdir -p ../../cache/themes/SuiteP/css/Dawn/style.css && cp ../../themes/SuiteP/css/Dawn/style.css ../../cache/themes/SuiteP/css/Dawn/style.css

echo "Generating css for Dusk subtheme"
../../SticInclude/vendor/scssphp/bin/pscss -s compressed ../../themes/SuiteP/css/Dusk/style.scss > ../../themes/SuiteP/css/Dusk/style.css && mkdir -p ../../cache/themes/SuiteP/css/Dusk/style.css && cp ../../themes/SuiteP/css/Dusk/style.css ../../cache/themes/SuiteP/css/Dusk/style.css

echo "Generating css for Day subtheme"
../../SticInclude/vendor/scssphp/bin/pscss -s compressed ../../themes/SuiteP/css/Day/style.scss > ../../themes/SuiteP/css/Day/style.css && mkdir -p ../../cache/themes/SuiteP/css/Day/style.css && cp ../../themes/SuiteP/css/Day/style.css ../../cache/themes/SuiteP/css/Day/style.css

echo "Generating css for Night subtheme"
../../SticInclude/vendor/scssphp/bin/pscss -s compressed ../../themes/SuiteP/css/Night/style.scss > ../../themes/SuiteP/css/Night/style.css && mkdir -p ../../cache/themes/SuiteP/css/Night/style.css && cp ../../themes/SuiteP/css/Night/style.css ../../cache/themes/SuiteP/css/Night/style.css

echo "Generating css for Noon subtheme"
../../SticInclude/vendor/scssphp/bin/pscss -s compressed ../../themes/SuiteP/css/Noon/style.scss > ../../themes/SuiteP/css/Noon/style.css && mkdir -p ../../cache/themes/SuiteP/css/Noon/style.css && cp ../../themes/SuiteP/css/Noon/style.css ../../cache/themes/SuiteP/css/Noon/style.css


# Delete tmpSrc directory and SuiteP-Icon-Font repository
rm -rf tmpSrc
rm -rf SuiteP-Icon-Font/ 