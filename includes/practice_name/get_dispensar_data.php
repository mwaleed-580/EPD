<?php
require "../../auth/config.php";

$practice_code = $_POST['practiceCode'];
$current_page = $_POST['page'];
$orderBy = $_POST['orderBy'];
$order = $_POST['order'];

if ($current_page > 1) {
    $offset = ($current_page - 1) * 100;
} else {
    $offset = $current_page - 1;
}

$start_time = microtime(true);

$query = "SELECT DispenserName, SUM(NumberofItems) AS TOTAL_ITEMS, SUM(NumberofEPSItems) AS TOTAL_EPS_ITEMS FROM DispensarData WHERE PracticeCode = '" . $practice_code . "' GROUP BY DispenserName";

if ($orderBy !== 'null') {
    $query .= " ORDER BY " . $orderBy . "";
}

if ($order !== 'null') {
    $query .= " " . $order . "";
}

$query .= " LIMIT 100 OFFSET " . $offset . "";


$result = mysqli_query($conn, $query);

$rows = array();
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

$end_time = microtime(true);
$execution_time = round(($end_time - $start_time) * 1000, 2);

$response = [
    'result_obj' => $rows,
    'execution_time' => $execution_time
];

header('Content-Type: application/json');
echo json_encode($response);
