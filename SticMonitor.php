<head>
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="refresh" content="5"/>
<style>
* {
    font-family: monospace;
    font-size: 12px;
}
table, th, td {
  border: 1px solid;
  border-collapse: collapse;
  padding: 10px;
}
</style>
</head>

<?php
// Define sugarEntry to allow further interaction with SugarCRM
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

// Script won't be executed if calling URL does not contain a certain token
$token = 'f7a179e895e5e01c22d0f2ee7b362381';
if (md5($_REQUEST['token']) != $token) {
    die('Not authorized');
}

// Host
$host = $_SERVER['HTTP_HOST'];

// Set time limits (seconds)
// $autoKillWaitTime = 60; // for local test
$autoKillWaitTime = ini_get('max_execution_time'); // if a query it's been executing longer than PHP max_execution_time it should be killed
$slowQueryLimit = 30;

// Get SugarCRM global data
require_once 'include/entryPoint.php';
global $current_user, $timedate;
$current_user = new User();
$current_user->getSystemUser();
$db = DBManagerFactory::getInstance();

// Get data from the max duration query currently running
$res = $db->query('SELECT * FROM information_schema.processlist where command != "sleep" order by time desc limit 1 ');
$query = $db->fetchByAssoc($res);
$queryId = $query['ID'];
$queryCommand = $query['COMMAND'];
$queryTime = $query['TIME'];
$queryInfo = $query['INFO'];

// Get the number of queries currently running
$queryCount = $db->getOne('SELECT count(*) FROM information_schema.processlist');

// Output info about current database status
echo 'SticMonitor (' . $host . '): ' . $queryCount . ' query(ies) currently running. Max duration query: ' . $queryTime . ' seconds.<br><br>';

// If no query lasts longer than $slowQueryLimit then end the script
if ($queryTime <= $slowQueryLimit) {
    // Output keyword for external monitoring
    echo 'SticMonitorOk';
    die('');
}

// Output max duration query data
echo '<table width="90%">';
echo '<tr><td width="25%">Query duration</td><td width="75%">' . $queryTime . ' seconds</td></tr>';
echo '<tr><td>Query text</td><td><textarea style="width:100%;" rows="10">' . $queryInfo . '</textarea></td></tr>';
echo '<tr><td>PHP max_execution_time</td><td>' . $autoKillWaitTime . ' seconds</td></tr>';
echo '<tr><td>Query will be autokilled in </td><td>' . ($autoKillWaitTime - $queryTime) . ' seconds (<a href="' . $_SERVER['REQUEST_URI'] . '&kill">Kill it now</a>)</td></tr>';
echo '</table><br><br>';

// Log database credentials decryption
$dbHostName = decrypt("WENTY0Vyekg4ZWVoS1R0UnI3TEFKQT09OjqXPqQDdPfypL2bZiqYnRPu", $_REQUEST['token']);
$dbUser = decrypt("b0tUZjl0NWJBT2s5a1ZickNIT2NnZz09OjoJkCrm/VgPA7RF+1DhU+QA", $_REQUEST['token']);
$dbPassword = decrypt("NWNQaENvRnEzNFJvN2lWdjBocVRqdz09OjoKli/lGeRuuQiGdcFzVwBA", $_REQUEST['token']);
$dbName = decrypt("Z3YybDNpcThnVnpwUVV6TGhvMWc3eGRKeXM5S3JSMmdhSDdyanpGYmptWT06Ot9TY1ZVrplHo1/sER5LBzk=", $_REQUEST['token']);

// Connecting to log database
$myLink = mysqli_connect($dbHostName, $dbUser, $dbPassword, $dbName); // Connecting to database
if (mysqli_connect_errno()) {
    printf("Connection error: %s\n", mysqli_connect_error());
}

// Log monitor general data
$syncDate = $timedate->fromDb(gmdate('Y-m-d H:i:s'));
$currentDate = $timedate->asUser($syncDate, $current_user);
$sql = "INSERT INTO instancesqueries.stic_monitor SET instance='$host', mysql_connects_num='$queryCount', mysql_connects_max_time='$queryTime', `time`=NOW(), id=uuid(), query_id='".md5($host . $queryId . $queryInfo)."'";
if ($myLink->query($sql) == true) {
    echo "Monitor general data logged.<br>";
}

// Log query text (if the query was previously logged, it won't be logged again) 
$sqlQuery = "INSERT INTO instancesqueries.stic_monitor_queries SET id='".md5($host . $queryId . $queryInfo)."', query='".$queryInfo."'";
if ($myLink->query($sqlQuery) == true) {
    echo "Query logged.<br>";
}

// Kill max duration query if it's been lasting longer than $autoKillWaitTime or URL contains "kill" parameter
if ($autoKillWaitTime < $queryTime || isset($_REQUEST['kill'])) {
    killQuery($db, $queryId);
}

// Closing database connection
$myLink->close();


/**
 * function to encrypt
 * @param string $data
 * @param string $key
 */
function encrypt($data,$key)
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted=openssl_encrypt($data, "aes-256-cbc", $key, 0, $iv);
    
    // return the encrypted string with $iv joined 
    return base64_encode($encrypted."::".$iv);
}
 

/**
 * function to decrypt
 * @param string $data
 * @param string $key
 */
function decrypt($data,$key)
{
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}


/**
 * Kill MySQL query thread
 *
 * @param Object $db db conection
 * @param String $id Query id to kill
 * @return void
 */
function killQuery($db, $id)
{
    $db->query("KILL $id");
    echo 'Killing query ' . $id;
    sleep(3);
    // Reload the script to analyze the next query 
    echo '<script>location.href="' . str_replace('&kill', '', $_SERVER['REQUEST_URI']) . '"</script>';
    die();
}