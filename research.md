# SinergiaCRM - Project Research

## Overview

SinergiaCRM is a Customer Relationship Management (CRM) system built on the **SuiteCRM** platform (which is a fork of SugarCRM Community Edition). It is written in **PHP 8.0+** and uses a **custom MVC framework**.

- **License**: GPL-3.0 (open source)
- **Framework**: Custom PHP MVC (not Laravel/Symfony)
- **Base**: SuiteCRM 7.x, which is based on SugarCRM CE 6.5.x

---

## Architecture

### Project Structure

| Directory | Purpose |
|-----------|---------|
| `modules/` | 183+ CRM modules (Accounts, Contacts, Leads, etc.) |
| `include/` | Core framework, MVC, utilities |
| `lib/` | Modern PSR-4 library code |
| `data/` | SugarBean, relationships, factory |
| `Api/` | REST API endpoints |
| `service/` | SOAP/REST web services |
| `custom/` | Customizations and extensions |
| `themes/` | UI themes (SuiteP, default) |
| `install/` | Installation wizard |
| `vendor/` | Composer dependencies |

---

## MVC Pattern Implementation

### Models (Beans)

Located in `/data/` and `/modules/<ModuleName>/`:

- **Base Model Class**: `/data/SugarBean.php` (6,454 lines)
  - All domain models extend `SugarBean`
  - Implements CRUD operations
  - Handles database interactions

- **Bean Factory**: `/data/BeanFactory.php`

- **Relationships**: `/data/Relationships/`
  - `One2MRelationship.php`, `M2MRelationship.php`, `One2OneRelationship.php`

### Views (Presentation Layer)

Located in `/include/MVC/View/` and `modules/<ModuleName>/views/`:

- **Base View Class**: `/include/MVC/View/SugarView.php` (2,126 lines)
- **Smarty Templates**: `/include/templates/`
- Module views: `modules/<ModuleName>/views/view.<action>.php`

### Controllers

Located in `/include/MVC/Controller/`:

- **Base Controller**: `/include/MVC/Controller/SugarController.php` (1,157 lines)
- **Controller Factory**: `/include/MVC/Controller/ControllerFactory.php`
- **Application Controller**: `/include/MVC/SugarApplication.php`

---

## Database & ORM

**Database Managers**: `/include/database/`
- `DBManager.php` - Abstract base class
- `MysqlManager.php`, `MysqliManager.php`, `MssqlManager.php`, `SqlsrvManager.php`

**ORM**: Custom ORM based on `SugarBean` class (Table-Data Gateway pattern)

---

## Configuration

**Main Configuration Files**:
- `config.php` - Main configuration (`$sugar_config` array)
- `config_override.php` - User overrides
- `.env.dist` / `.env.test` - Environment variables

**Configuration Loading** (via `/include/entryPoint.php`):
1. `config.php`
2. `config_override.php`
3. Composer autoloader

---

## `disable_count_query` Configuration

### Purpose

The `disable_count_query` config is a performance optimization setting that **disables the COUNT query** used for pagination in list views.

### How It Works

When listing records (e.g., in list views, subpanels), the system normally executes:
1. **Main query**: Retrieve the records for the current page
2. **COUNT query**: Count total records for pagination controls

When `disable_count_query` is enabled:
- The COUNT query is **skipped**
- Instead, the system fetches **one extra row** (`limit + 1`) to determine if there are more records
- This reduces database load but removes exact row counts from the UI

### Configuration

To enable, add to `config_override.php`:
```php
$sugar_config['disable_count_query'] = true;
```

Or alternatively (used in tests):
```php
$sugar_config['disable_count_query'] = 1;
```

### Affected Files (19 occurrences)

| File | Line(s) | Usage |
|------|---------|-------|
| `data/SugarBean.php` | 1264, 1470, 4364, 4445 | Main bean list processing |
| `include/ListView/ListViewDisplay.php` | 284 | List view display |
| `include/ListView/ListViewData.php` | 270 | List view data |
| `include/ListView/ListView.php` | 1375, 1431, 1521 | List view |
| `include/ListView/ListViewSubPanel.php` | 654, 706, 779 | Subpanel list view |
| `include/SearchForm/SugarSpot.php` | 271 | Spot search |
| `modules/Emails/include/ListView/ListViewDataEmailsSearchOnCrm.php` | 109 | Email search |
| `include/EditView/SugarVCR.php` | 140 | VCR (pagination) |
| `service/v4/SugarWebServiceImplv4.php` | 356 | SOAP API v4 |
| `service/v3_1/SugarWebServiceImplv3_1.php` | 774 | SOAP API v3.1 |

### Code Example (SugarBean.php:1264)

```php
// If disabled, skip the count query
if (empty($sugar_config['disable_count_query']) || $toEnd) {
    $rows_found = $this->_get_num_rows_in_query($query_row_count, $use_count_query);
    // ...
} else {
    // Fetch limit + 1 rows to determine pagination
    if ((empty($limit) || $limit == -1)) {
        $limit = $max_per_page + 1;
    }
}
```

### Impact

- **Pros**: Reduces database queries, improves performance on large datasets
- **Cons**: 
  - No exact total count in list view pagination
  - "Showing x-y of ?" instead of "Showing x-y of 500"
  - May affect navigation (Next/Previous buttons)

---

## Additional Components

### REST/SOAP APIs
- `/Api/V8/` - REST API v8 (modern)
- `/service/` - Legacy SOAP/REST (v2, v3, v4)

### Service Layer
- `/service/core/`
- `/service/v2/`, `v3/`, `v4/`

### Customization
- `/custom/` - Custom code directory
- `/custom/modules/` - Module extensions
- `/custom/Extension/` - Extension framework
