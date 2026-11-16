<?php
/**
 * https://github.com/SinergiaTIC/SinergiaCRM/pull/1524
 *
 * Repairs corrupted or legacy HTML-encoded JSON data in AWF tables
 * (`stic_awf_forms.configuration` and `stic_awf_links.submitted_data`).
 *
 * For each affected row, applies the first matching action:
 *   1. Normalize: Re-encodes HTML entities inside JSON values as valid JSON escapes.
 *   2. Decode legacy: Converts fully entity-encoded JSON strings into canonical JSON.
 *   3. Report: Leaves unparseable rows untouched and lists them as [MANUAL].
 *
 * Safety & Idempotency:
 *   - Backs up modified rows to `zz_backup_awf_json_repair_<table>_<timestamp>` first.
 *   - Updates via raw SQL escaping without altering `date_modified`.
 *   - Safe to execute multiple times (already-repaired rows are skipped).
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * True when the string parses as JSON.
 */
function awfJsonRepairIsValid(string $doc): bool
{
    json_decode($doc, true);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Decodes entities inside leaf values of a JSON document and re-encodes them as
 * proper JSON escapes (e.g. "Org &quot;N&quot;" becomes "Org \"N\"").
 *
 * Self-contained copy of stic_AWF_FormsUtils::normalizeConfigurationJson()
 * (modules/stic_AWF_Forms/Utils.php), so this script does not depend on code
 * from the update being already in place. Unparseable input is returned as is.
 */
function awfJsonRepairNormalize(string $json): string
{
    $data = json_decode($json, true);
    if (!is_array($data)) {
        return $json;
    }
    $out = $json;
    $seen = [];
    array_walk_recursive($data, function (&$value) use (&$out, &$seen): void {
        if (!is_string($value) || strpos($value, '&') === false || isset($seen[$value])) {
            return;
        }
        $seen[$value] = true;
        $decoded = $value;
        while (($next = html_entity_decode($decoded, ENT_QUOTES, 'UTF-8')) !== $decoded) {
            $decoded = $next;
        }
        if ($decoded !== $value) {
            $encodedOld = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $encodedNew = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if (is_string($encodedOld) && is_string($encodedNew)) {
                $out = str_replace($encodedOld, $encodedNew, $out);
            }
        }
    });
    return $out;
}

/**
 * Escapes a value for a raw SQL literal, replicating the strategy of the fixed
 * stic_AWF_Forms::save(): raw MySQL escaping, because DBManager::quote()
 * decodes HTML entities.
 */
function awfJsonRepairEscape(string $value): string
{
    global $db;
    $mysqli = $db->getDatabase();
    if ($mysqli !== null && function_exists('mysqli_real_escape_string')) {
        return mysqli_real_escape_string($mysqli, $value);
    }
    return addslashes($value);
}

/**
 * Copies the given rows (full row) into a backup table created for this run.
 */
function awfJsonRepairBackup(string $table, array $ids): string
{
    global $db;
    $backupTable = 'zz_backup_awf_json_repair_' . $table . '_' . date('Ymd_His');
    $idList = implode("','", array_map('awfJsonRepairEscape', $ids));
    $db->query("CREATE TABLE {$backupTable} LIKE {$table}");
    $db->query("INSERT INTO {$backupTable} SELECT * FROM {$table} WHERE id IN ('{$idList}')");
    return $backupTable;
}

/**
 * Stores the repaired document with a targeted UPDATE (pristine bytes,
 * date_modified untouched), like the fixed stic_AWF_Forms::save().
 */
function awfJsonRepairStore(string $table, string $column, string $id, string $doc): void
{
    global $db;
    $safeId = awfJsonRepairEscape($id);
    $safeDoc = awfJsonRepairEscape($doc);
    $db->query("UPDATE {$table} SET {$column} = '{$safeDoc}' WHERE id = '{$safeId}' AND deleted = 0");
}

// ---------------------------------------------------------------------------
// Main flow
// ---------------------------------------------------------------------------

$db = DBManagerFactory::getInstance();
if (!$db instanceof DBManager) {
    $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ': DBManager is not set');
    echo 'DBManager not available. Repair skipped.<br />';
    return;
}

// Table => column to repair, plus the entity flags its read paths decode with.
$tables = [
    'stic_awf_forms' => ['column' => 'configuration', 'decodeFlags' => ENT_QUOTES],
    'stic_awf_links' => ['column' => 'submitted_data', 'decodeFlags' => ENT_COMPAT],
];

$repairedCount = [];
$manualRows = [];
$backupTables = [];

foreach ($tables as $table => $def) {
    $column = $def['column'];
    $flags = $def['decodeFlags'];
    $repairedCount[$table] = 0;
    $manualRows[$table] = [];

    // Cheap pre-filter: only rows whose raw JSON fails, or that contain
    // entity-like sequences, need the (slower) PHP evaluation.
    $result = $db->query(
        "SELECT id, name, {$column} AS doc FROM {$table}
         WHERE deleted = 0
           AND (JSON_VALID({$column}) = 0
                OR {$column} REGEXP '&(quot|amp|lt|gt|#0?39|#x27|#0?34|#x22);')"
    );

    $pending = [];
    while ($row = $db->fetchByAssoc($result, false)) {
        $raw = (string) $row['doc'];
        if (trim($raw) === '') {
            continue; // Never configured / empty: nothing to repair.
        }

        // Same semantics as the runtime read paths of each module.
        $decoded = html_entity_decode($raw, $flags, 'UTF-8');
        $rawOk = awfJsonRepairIsValid($raw);
        $decodedOk = awfJsonRepairIsValid($decoded);

        $repaired = null;
        $category = null;

        if ($rawOk && $decodedOk) {
            // Valid and readable: canonicalize only if entities need re-encoding.
            $normalized = awfJsonRepairNormalize($raw);
            if ($normalized !== $raw
                && awfJsonRepairIsValid(html_entity_decode($normalized, $flags, 'UTF-8'))) {
                $category = 'normalized';
                $repaired = $normalized;
            }
        } elseif ($rawOk && !$decodedOk) {
            // Valid in DB but broken by the read decode (entity sequences that
            // decode into raw quotes): the form already misbehaves.
            $normalized = awfJsonRepairNormalize($raw);
            if ($normalized !== $raw
                && awfJsonRepairIsValid(html_entity_decode($normalized, $flags, 'UTF-8'))) {
                $category = 'repaired (broken by read decode)';
                $repaired = $normalized;
            } else {
                $category = 'MANUAL (structural corruption)';
            }
        } elseif (!$rawOk && $decodedOk) {
            // Legacy entity-encoded document: storing the decoded bytes keeps
            // exactly the current read behavior, in canonical form.
            $category = 'repaired (legacy encoding)';
            $repaired = $decoded;
        } else {
            $category = 'MANUAL (unparseable JSON)';
        }

        if ($repaired !== null) {
            $pending[] = ['id' => $row['id'], 'doc' => $repaired, 'category' => $category];
        } elseif ($category !== null) {
            $manualRows[$table][] = $row['id'] . ' "' . mb_substr($row['name'] ?? '', 0, 50) . '"';
        }
    }

    if (!empty($pending)) {
        $backupTables[] = awfJsonRepairBackup($table, array_column($pending, 'id'));
        foreach ($pending as $item) {
            awfJsonRepairStore($table, $column, $item['id'], $item['doc']);
            $repairedCount[$table]++;
            echo "[{$table}] {$item['category']}: {$item['id']}<br />";
        }
    }

    if (!empty($manualRows[$table])) {
        echo "[{$table}] Rows needing MANUAL repair (not modified):<br />";
        foreach ($manualRows[$table] as $manualRow) {
            echo "&nbsp;&nbsp;- {$manualRow}<br />";
        }
    }

    $GLOBALS['log']->info(
        'Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF JSON repair on {$table}: "
        . $repairedCount[$table] . ' rows repaired, ' . count($manualRows[$table]) . ' rows reported as manual.'
    );
}

$backupText = empty($backupTables) ? 'none' : implode(', ', $backupTables);
$summaryLine = 'RepairCorruptedAwfJson - rows repaired: '
    . implode(', ', array_map(function ($table) use ($repairedCount) {
        return "{$table}={$repairedCount[$table]}";
    }, array_keys($repairedCount)))
    . '; backup tables: ' . $backupText . '.';
echo $summaryLine . '<br />';

// Surface the results to the update runner: this file is required inside
// SticRunScripts::executePHPFile() / SticUpdate::runScripts(), whose scope holds
// the $infos array reported in the update output. The per-row echo above is
// captured (and discarded) by the runner, so the summary and the manual rows
// must go through $infos to reach the update report. In contexts without a
// runner (e.g. CLI harnesses) this block is simply skipped.
if (isset($infos) && is_array($infos)) {
    $infos[] = $summaryLine;
    foreach ($tables as $table => $def) {
        if (!empty($manualRows[$table])) {
            $infos[] = 'RepairCorruptedAwfJson - MANUAL repair needed in ' . $table
                . ' (rows not modified): ' . implode('; ', $manualRows[$table]);
        }
    }
}
foreach ($tables as $table => $def) {
    if (!empty($manualRows[$table])) {
        $GLOBALS['log']->warn(
            'Line ' . __LINE__ . ': ' . __METHOD__ . ': AWF JSON repair - manual rows in '
            . $table . ': ' . implode('; ', $manualRows[$table])
        );
    }
}
