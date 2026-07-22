<?php
if (!defined('sugarEntry')) define('sugarEntry', true);

// Mock BeanFactory class
class BeanFactory
{
    public static function getBean($module, $id = null)
    {
        $b = new MockBean();
        if ($id !== null) {
            return ($id === 'existing-id' || $id === 'id1' || $id === 'id2') ? $b : null;
        }
        return $b;
    }
}

class MockDb
{
    public $lastQuery = '';
    private $fetchCount = 0;
    public function query($sql)
    {
        $this->lastQuery = $sql;
        return $this;
    }
    public function fetchByAssoc($result)
    {
        $this->fetchCount++;
        if ($this->fetchCount > 5) return false;
        $ids = array('id1', 'id2', 'id3', 'id4', 'id5');
        $idx = $this->fetchCount - 1;
        if ($idx < count($ids)) {
            return array('id' => $ids[$idx]);
        }
        return false;
    }
    public function quote($s)
    {
        return addslashes($s);
    }
}

class MockBean
{
    public $module_dir = 'Accounts';
    public $table_name = 'accounts';
    public $field_defs = array(
        'name' => array('type' => 'varchar', 'vname' => 'LBL_NAME'),
        'date_entered' => array('type' => 'datetime', 'vname' => 'LBL_DATE_ENTERED'),
    );
    public function ACLAccess($action)
    {
        return true;
    }
}

$passed = 0;
$failed = 0;

function test($label, $condition, $detail = '')
{
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "  ✓ $label\n";
    } else {
        $failed++;
        echo "  ✗ $label" . ($detail ? " — $detail" : '') . "\n";
    }
}

echo "=== TtsListviewOrder Tests ===\n\n";

// Need to set up the global $db and remap BeanFactory
// Since this is complex, let me test the key methods directly

require_once '/application/sinergiacrm/custom/include/TextToSpeech/Entrypoints/ttsListviewOrder.php';

$orderHelper = new TtsListviewOrder();

// We can use reflection to test private methods or test public method with known inputs
// Let's test getOrderedIds with various contexts

$mockDb = new MockDb();
global $db;
$db = $mockDb;

// Test 1: Empty context returns original UIDs
$result = $orderHelper->getOrderedIds(array(
    'module' => '',
    'uids' => array(),
));
test('Empty context returns empty array', $result === array());

$result = $orderHelper->getOrderedIds(array(
    'module' => '',
    'uids' => array('id1', 'id2'),
));
test('Empty module returns original uids', $result === array('id1', 'id2'));

$result = $orderHelper->getOrderedIds(array(
    'module' => 'Accounts',
    'uids' => array(),
));
test('Empty uids returns empty array', $result === array());

echo "\n--- buildOrderBy tests ---\n";

$reflection = new ReflectionClass('TtsListviewOrder');
$buildOrderBy = $reflection->getMethod('buildOrderBy');
$buildOrderBy->setAccessible(true);

$seed = new MockBean();

$result = $buildOrderBy->invoke($orderHelper, $seed, '', 'ASC');
test('Empty orderBy returns empty string', $result === '');

$result = $buildOrderBy->invoke($orderHelper, $seed, 'name', 'ASC');
test('buildOrderBy ASC', strpos($result, 'ASC') !== false);

$result = $buildOrderBy->invoke($orderHelper, $seed, 'name', 'DESC');
test('buildOrderBy DESC', strpos($result, 'DESC') !== false);

$result = $buildOrderBy->invoke($orderHelper, $seed, 'name', 'asc');
test('buildOrderBy lowercase asc → ASC', strpos($result, 'ASC') !== false && strpos($result, 'asc') === false);

$result = $buildOrderBy->invoke($orderHelper, $seed, 'name', 'ASC');
test('buildOrderBy includes table name', strpos($result, 'accounts.name') !== false);

echo "\n--- getOrderedIds with DB mock ---\n";

// Test with valid module and uids - should produce a query
$db = new MockDb();
$result = $orderHelper->getOrderedIds(array(
    'module' => 'Accounts',
    'uids' => array('id1', 'id2', 'id3'),
    'select_entire_list' => '0',
));
test('getOrderedIds returns array (empty from mock DB)', is_array($result));

// Check the SQL was built correctly
$sql = $db->lastQuery;
test('SQL includes accounts table', strpos($sql, 'FROM accounts') !== false);
test('SQL includes deleted=0', strpos($sql, 'deleted = 0') !== false);
test('SQL includes IN clause', strpos($sql, "IN ('id1','id2','id3')") !== false);
test('SQL has no ORDER BY', strpos($sql, 'ORDER BY') === false);

echo "\n--- getOrderedIds with current_query_by_page ---\n";

$db = new MockDb();
$result = $orderHelper->getOrderedIds(array(
    'module' => 'Accounts',
    'uids' => array('id1'),
    'current_query_by_page' => json_encode(array(
        'searchFields' => array(
            'accounts.name' => 'test',
        ),
    )),
    'orderBy' => 'date_entered',
    'lvso' => 'DESC',
));
$sql = $db->lastQuery;
test('SQL with search WHERE', strpos($sql, "accounts.name LIKE '%test%'") !== false);
test('SQL with ORDER BY date_entered DESC', strpos($sql, "date_entered DESC") !== false);
test('SQL WITH search AND IN clause', strpos($sql, 'IN') !== false);
test('SQL with search AND deleted', strpos($sql, 'deleted = 0') !== false);

echo "\n--- getOrderedIds with select_entire_list=1 ---\n";

$db = new MockDb();
$result = $orderHelper->getOrderedIds(array(
    'module' => 'Accounts',
    'uids' => array('id1', 'id2'),
    'select_entire_list' => '1',
    'current_query_by_page' => json_encode(array(
        'searchFields' => array(
            'accounts.name' => 'test',
        ),
    )),
    'orderBy' => 'name',
    'lvso' => 'ASC',
));
$sql = $db->lastQuery;
test('select_entire_list=1 omits IN clause', strpos($sql, 'IN') === false);
test('select_entire_list=1 still has WHERE', strpos($sql, "accounts.name LIKE '%test%'") !== false);

echo "\n=== Resultados: $passed passed, $failed failed ===\n";
exit($failed > 0 ? 1 : 0);
