# Implementation Plan: Async List Count for ListView

## Overview

This implementation adds a new configuration option `async_list_count` that allows the list view to load the record count asynchronously after the initial page load. This improves perceived performance by showing the list first, then loading the total count in the background.

---

## 1. Add New Configuration

### File: `config_override.php`

Add default configuration (or in `config.php` for default value):

```php
// STIC-Custom OC - 20260309 - Add async_list_count configuration
$sugar_config['async_list_count'] = false; // Default disabled
// END STIC-Custom OC
```

---

## 2. Modify ListViewData.php

### File: `include/ListView/ListViewData.php`

**Current Line 270**:
```php
$totalCounted = empty($GLOBALS['sugar_config']['disable_count_query']);
```

**New Logic** (around line 270-280):
```php
// STIC-Custom OC - 20260309 - Check if async count is enabled
$useAsyncCount = !empty($GLOBALS['sugar_config']['async_list_count']);
// totalCounted is false when async is enabled (count will be loaded via AJAX)
$totalCounted = empty($GLOBALS['sugar_config']['disable_count_query']) && !$useAsyncCount;
// END STIC-Custom OC
```

**Around line 538** (where pageData is built):
Add flag to indicate async count is pending:
```php
// STIC-Custom OC - 20260309 - Add flag to indicate async count is pending
$pageData['offsets']['asyncCountPending'] = $useAsyncCount && !$totalCounted;
// END STIC-Custom OC
```

---

## 3. Create AJAX Endpoint

### Create Entry Point Class

First, create the entry point class file:

**New File**: `SticInclude/AsyncListCount.php`

```php
<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2025 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

// STIC-Custom OC - 20260309 - Async list count entry point
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

// Check authentication
if (!$current_user->isAuthenticated()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    return;
}

$module = $_GET['module'] ?? '';
$where = $_GET['where'] ?? '';

// Validate module
if (empty($module)) {
    echo json_encode(['success' => false, 'error' => 'Module not specified']);
    return;
}

$bean = BeanFactory::getBean($module);
if (!$bean) {
    echo json_encode(['success' => false, 'error' => 'Invalid module']);
    return;
}

// Create count query
$ret_array = $bean->create_new_list_query('', $where, [], [], 0, '', true, $bean, false);
$countQuery = $bean->create_list_count_query($ret_array['select'] . $ret_array['from'] . $ret_array['where']);

// Execute count
$db = DBManagerFactory::getInstance();
$result = $db->query($countQuery);
$row = $db->fetchByAssoc($result);
$total = intval($row['c'] ?? $row['count'] ?? 0);

echo json_encode(['success' => true, 'total' => $total]);
// END STIC-Custom OC
```

### Register Entry Point

**File**: `custom/Extension/application/Ext/EntryPointRegistry/SticEntryPoints.php`

Add at the end before the closing PHP tag:

```php
// STIC-Custom OC - 20260309 - Async list count for ListView
$entry_point_registry['asyncListCount'] = array('file' => 'SticInclude/AsyncListCount.php', 'auth' => true);
// END STIC-Custom OC
```

---

## 4. Modify Smarty Templates

### File: `themes/SuiteP/include/ListView/ListViewPagination.tpl`

**Current Line 95**:
```smarty
({if $pageData.offsets.lastOffsetOnPage == 0}0{else}{$pageData.offsets.current+1}{/if} - {$pageData.offsets.lastOffsetOnPage} {$navStrings.of} {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$pageData.offsets.total}{if $pageData.offsets.lastOffsetOnPage != $pageData.offsets.total}+{/if}{/if})
```

**New Logic**:
```smarty
{* STIC-Custom OC - 20260309 - Async count loading *}
{if $pageData.offsets.asyncCountPending}
<span class="async-count-loading" data-module="{$pageData.bean.moduleDir}" data-where="{$where}" data-offset="{$pageData.offsets.current}" data-limit="{$pageData.offsets.next}"><i class="fa fa-spinner fa-spin"></i></span>
{else}
({if $pageData.offsets.lastOffsetOnPage == 0}0{else}{$pageData.offsets.current+1}{/if} - {$pageData.offsets.lastOffsetOnPage} {$navStrings.of} {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$pageData.offsets.total}{if $pageData.offsets.lastOffsetOnPage != $pageData.offsets.total}+{/if}{/if})
{/if}
{* END STIC-Custom OC *}
```

Also apply same change to:
- `themes/SuiteP/include/ListView/ListViewPaginationTop.tpl`
- `themes/SuiteP/include/ListView/ListViewPaginationBottom.tpl`
- Other theme templates

---

## 5. Add JavaScript

### Modify Source File

**File**: `jssource/src_files/include/javascript/sugar_3.js`

Add at the end of the file (before the last closing lines):

```javascript
// STIC-Custom OC - 20260309 - Async count loading for list views
function loadAsyncListCount() {
    var asyncCountElements = document.querySelectorAll('.async-count-loading');
    if (!asyncCountElements.length) return;

    asyncCountElements.forEach(function(el) {
        var module = el.dataset.module;
        var where = el.dataset.where || '';
        var url = 'index.php?entryPoint=asyncListCount&module=' + encodeURIComponent(module) + 
                  '&where=' + encodeURIComponent(where);

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        el.innerHTML = response.total;
                        el.classList.remove('async-count-loading');
                        el.classList.add('async-count-loaded');
                    }
                } catch (e) {
                    console.error('Error parsing async count response:', e);
                }
            }
        };
        xhr.send();
    });
}

// Load async counts on page ready
document.addEventListener('DOMContentLoaded', function() {
    // Delay slightly to prioritize main content loading
    setTimeout(loadAsyncListCount, 100);
});
// END STIC-Custom OC
```

### Modify Compiled File

**File**: `include/javascript/sugar_3.js`

Apply the same changes as above (the compiled/minified version that is actually served to users).

---

## 6. Files to Modify/Create

| File | Action | Lines |
|------|--------|-------|
| `config_override.php` | Add config default | New |
| `include/ListView/ListViewData.php` | Modify count logic | ~270, ~538 |
| `SticInclude/AsyncListCount.php` | Create entry point class | New |
| `custom/Extension/application/Ext/EntryPointRegistry/SticEntryPoints.php` | Register entry point | ~55 |
| `themes/SuiteP/include/ListView/ListViewPagination.tpl` | Add async placeholder | ~95 |
| `themes/SuiteP/include/ListView/ListViewPaginationTop.tpl` | Add async placeholder | Similar |
| `themes/SuiteP/include/ListView/ListViewPaginationBottom.tpl` | Add async placeholder | Similar |
| `jssource/src_files/include/javascript/sugar_3.js` | Add AJAX fetch logic | End of file |
| `include/javascript/sugar_3.js` | Add AJAX fetch logic | End of file |

---

## 7. Test Scenarios

- [ ] Enable config, verify list loads without count
- [ ] Count appears asynchronously after list renders
- [ ] Loading spinner displays while counting
- [ ] Pagination works correctly with async count
- [ ] Works with search/filter
- [ ] Works with different themes
- [ ] Existing `disable_count_query` still works
- [ ] AJAX endpoint returns correct count
- [ ] Security: ensure only authenticated users can access
- [ ] Run repair and rebuild to apply entry point changes

---

## 8. Future Enhancements (Out of Scope for This Phase)

- SubPanel count (as mentioned in requirements)
- Cache count results
- Debounce rapid count requests
