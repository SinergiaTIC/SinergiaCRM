# Proposal: Subpanel Template Enhancements

## Overview

Extend the subpanel loop system in PDF Templates with **aggregation**, **sorting**, and **filtering** capabilities for related record tables.

---

## Current State

```html
<!--$subpanel:stic_payments_contacts-->
<tr><td>$stic_payments_name</td><td>$stic_payments_amount</td></tr>
<!--/$subpanel:stic_payments_contacts-->
```

- Loops over all related records (up to 100, hardcoded)
- No ordering guarantee (database default order)
- No filtering capability
- No aggregation functions (SUM, COUNT, AVG, etc.)
- Enum/dropdown fields on related records render as `&nbsp;` (separate bug)

---

## Proposed Features

### 1. Aggregation Functions

**Syntax:** `$FUNC:tablename:fieldname`

| Function     | Syntax                              | Example                                |
|-------------|--------------------------------------|----------------------------------------|
| SUM         | `$SUM:tablename:fieldname`          | `$SUM:stic_payments:amount`           |
| COUNT       | `$COUNT:tablename:fieldname`        | `$COUNT:stic_payments:id`             |
| AVG         | `$AVG:tablename:fieldname`          | `$AVG:stic_payments:amount`           |
| MIN         | `$MIN:tablename:fieldname`          | `$MIN:stic_payments:payment_date`     |
| MAX         | `$MAX:tablename:fieldname`          | `$MAX:stic_payments:amount`           |

**Template example:**

```html
<!--$subpanel:stic_payments_contacts-->
<tr>
  <td>$stic_payments_name</td>
  <td>$stic_payments_amount</td>
</tr>
<!--/$subpanel:stic_payments_contacts-->
<tr>
  <td colspan="2"><strong>Total: $SUM:stic_payments:amount</strong></td>
</tr>
```

**Implementation:**
- During `parseSubpanels()`, accumulate values for each `$FUNC:...` placeholder
- After the loop, replace placeholders with computed values
- Functions operate on the filtered/sorted result set (see below)
- Format: numbers get `number_format(2)` by default, dates as-is

**UI integration:**
- Add "Insert Aggregate" button next to "Insert Field" in the Subpanel section
- Dropdown with: SUM, COUNT, AVG, MIN, MAX
- Inserts the appropriate `$FUNC:...` placeholder

---

### 2. Sorting

**Syntax:** `<!--$subpanel:relationship:order=field;dir=ASC-->`

Extend the subpanel start tag with optional parameters using semicolon-separated key=value pairs:

| Parameter   | Value      | Default | Description                     |
|------------|-----------|---------|---------------------------------|
| `order`    | field name | none   | Field to sort by               |
| `dir`      | ASC / DESC | ASC     | Sort direction                 |

**Template example:**

```html
<!--$subpanel:stic_payments_contacts:order=amount;dir=DESC-->
<tr>
  <td>$stic_payments_name</td>
  <td>$stic_payments_amount</td>
</tr>
<!--/$subpanel:stic_payments_contacts-->
```

**Parsing strategy:**
- Parse options from the start tag regex (group 3 extended)
- After fetching related beans via `get_linked_beans()`, sort them in PHP using `usort()`
- Works on any field type (numeric, string, date)

**UI integration:**
- In the Subpanel section, after selecting a subpanel and field:
  - Add a "Sort" dropdown: ASC / DESC / None
  - When "Insert Loop" is clicked, include sort options in the tag

---

### 3. Filtering

**Syntax:** `<!--$subpanel:relationship:filter=field:operator:value-->`

| Operator    | Meaning                | Example                        |
|------------|------------------------|--------------------------------|
| `eq` or `=`| Equal                  | `status=Completed`             |
| `neq` or `!=` | Not equal          | `status!=Pending`              |
| `gt` or `>` | Greater than          | `amount>100`                   |
| `gte` or `>=` | Greater or equal    | `amount>=50`                   |
| `lt` or `<` | Less than             | `amount<500`                   |
| `lte` or `<=` | Less or equal       | `payment_date<=2026-06-01`     |
| `like`      | Contains (substring)  | `name=Monthly` (with like)     |
| `in`        | One of (comma list)   | `status=Completed,Pending`     |

**Template examples:**

*Single filter:*
```html
<!--$subpanel:stic_payments_contacts:filter=status:eq:Completed-->
```

*Multiple filters (AND logic):*
```html
<!--$subpanel:stic_payments_contacts:filter=status:eq:Completed;amount:gte:50-->
```

*Combined with sort:*
```html
<!--$subpanel:stic_payments_contacts:order=amount;dir=DESC;filter=status:eq:Completed;payment_type:eq:Donation-->
```

**Implementation:**
- Parse filter options from the start tag
- After fetching beans, apply filters in PHP before sorting
- Multiple filters are ANDed together
- Type-aware comparison: numeric for numbers, string for text, date for dates

**UI integration:**
- In the Subpanel section:
  - "Filter Field" dropdown
  - "Filter Operator" dropdown (eq, neq, gt, lt, like, in)
  - "Filter Value" text input
  - "Add Filter" button (adds to a list)
  - Inserted into the loop tag when "Insert Loop" is clicked

---

### 4. Row Limit

**Syntax:** `<!--$subpanel:relationship:limit=10-->`

| Parameter | Value | Default | Description          |
|----------|-------|---------|----------------------|
| `limit`  | number | 100     | Maximum rows to show |

**Example:**
```html
<!--$subpanel:stic_payments_contacts:order=amount;dir=DESC;limit=5-->
<tr><td>$stic_payments_name</td><td>$stic_payments_amount</td></tr>
<!--/$subpanel:stic_payments_contacts-->
```

---

## Full Example Template

```html
<h1>$contacts_name</h1>
<p><strong>Email:</strong> $contacts_email1</p>

<hr>
<h2>Recent Large Payments</h2>
<table border="1" cellpadding="5">
  <tr>
    <th>Name</th>
    <th>Amount</th>
    <th>Date</th>
    <th>Type</th>
    <th>Status</th>
  </tr>
  <!--$subpanel:stic_payments_contacts:order=amount;dir=DESC;limit=5;filter=status:eq:Completed-->
  <tr>
    <td>$stic_payments_name</td>
    <td align="right">$stic_payments_amount</td>
    <td>$stic_payments_payment_date</td>
    <td>$stic_payments_payment_type</td>
    <td>$stic_payments_status</td>
  </tr>
  <!--/$subpanel:stic_payments_contacts-->
  <tr>
    <td colspan="5" align="right">
      <strong>Total (Completed): $SUM:stic_payments:amount</strong> &nbsp;
      (Count: $COUNT:stic_payments:id)
    </td>
  </tr>
</table>
```

---

## Tag Syntax Summary

```
<!--$subpanel:relationship[:parentModule][:key=value[;key=value...]]-->
  ... row template with $tablename_fieldname placeholders ...
<!--/$subpanel:relationship[:parentModule]-->
```

| Parameter   | Format                                   | Example                                |
|------------|------------------------------------------|----------------------------------------|
| `order`    | `order=fieldname`                        | `order=amount`                         |
| `dir`      | `dir=ASC\|DESC`                           | `dir=DESC`                             |
| `limit`    | `limit=number`                           | `limit=10`                             |
| `filter`   | `filter=field:operator:value`            | `filter=status:eq:Completed`           |

---

## Implementation Plan

### Phase 1 - Aggregation (simplest)
- Extend `parseSubpanels()` to detect `$FUNC:tablename:fieldname` in the loop body
- Accumulate values during iteration
- Replace placeholders after the loop
- Add "Insert Aggregate" button in UI

### Phase 2 - Sorting & Limit
- Extend `SUBPANEL_START_PATTERN` regex to capture option string
- Parse `order`, `dir`, `limit` from option string
- Sort beans with `usort()` before iterating
- Slice array with `limit`
- Update UI to include sort/limit controls

### Phase 3 - Filtering
- Parse `filter` options (multiple supported)
- Apply `array_filter()` with type-aware comparison
- Filters applied BEFORE sorting and limiting
- Update UI with filter controls

---

## Files to Modify

| File | Changes |
|------|---------|
| `modules/AOS_PDF_Templates/templateParser.php` | New methods: `parseSubpanelOptions()`, `applySubpanelFilters()`, `applySubpanelSort()`, `computeAggregates()`. Extend existing `parseSubpanels()` and `parseNestedSubpanel()`. |
| `custom/modules/AOS_PDF_Templates/SticUtils.js` | Extend `insertSubpanelLoop()` to include options. Add aggregate functions. New helper functions for filter UI. |
| `custom/modules/AOS_PDF_Templates/views/view.edit.php` | Add aggregate button, sort direction dropdown, filter controls. |
| `custom/Extension/modules/AOS_PDF_Templates/Ext/Language/*.SticLang.php` | New labels for aggregate functions, filter operators, sort directions. |

---

## Risks / Considerations

1. **Enum/dropdown field values** on related beans currently render empty (`&nbsp;`) — this is a pre-existing bug caused by `get_linked_beans()` not populating all fields. Sorting/filtering on empty fields would be useless. **Fix required:** call `$bean->retrieve()` to fully load each related bean before processing.

2. **Parent module context** — the existing `parentKey` parameter (third group) currently targets a specific parent bean. The new options syntax must not conflict with this. Suggestion: use `key:value` pairs for options (with `=` separator), and keep the plain `key` format for parentKey (backward compatible).

3. **Performance** — if a subpanel has hundreds of records, fully loading each bean (`retrieve()`) and sorting in PHP could be slow. Mitigation: keep the `limit` default at 100, and consider pagination in the future.

4. **HTMLPurifier** — the extended comment syntax must survive purification. The existing bug (placeholder stripping between table rows) applies here too. The `safePurify()` fix already in place should handle extended syntax as long as the tag format stays within `<!--...-->`.
