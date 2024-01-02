<?php

// Add js file for some customizations in Popup views
$js_groupings[] = $newGrouping = array(
    'SticInclude/js/SticPopupCustomizations.js' => 'include/javascript/sugar_grp1.js',
);

// Adding selectize library to JSGroupings.
// JSGroupings are file packages containing one or more minified JavaScript libraries.
// The groupings enhance system performance by reducing the number of downloaded files for a given page.
// The resulting files will be created inside the /cache/ folder.
$js_groupings[] = $newGrouping = array(
    'SticInclude/vendor/selectize/js/selectize.min.js' => 'include/javascript/sugar_grp1.js',
    'SticInclude/js/SticSelectize.js' => 'include/javascript/sugar_grp1.js',
);

// Overrides isValidEmail function and fix STIC#301
$js_groupings[] = $newGrouping = array(
    'SticInclude/js/SticCustomIsValidEmailFunction.js' => 'include/javascript/sugar_grp1.js',
);

// Add autosize library & runit
$js_groupings[] = $newGrouping = array(
    'SticInclude/vendor/autosize/dist/autosize.min.js' => 'include/javascript/sugar_grp1.js',
    'SticInclude/js/SticAutosize.js' => 'include/javascript/sugar_grp1.js',
);

// Add qtip library in all pages and custom qtip calls
$js_groupings[] = $newGrouping = array(
    'include/javascript/qtip/jquery.qtip.min.js' => 'include/javascript/sugar_grp1.js',
    'SticInclude/js/SticQtip.js' => 'include/javascript/sugar_grp1.js',
    
);
// Add qtip library in all pages and custom qtip calls
$js_groupings[] = $newGrouping = array(
    'SticInclude/js/SticGetAdditionalDetails.js' => 'include/javascript/sugar_grp1.js',
);