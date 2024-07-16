<?php
require "../auth/config.php";

$countWhereClauses = isset($_POST['countWhereClauses']) ? json_decode($_POST['countWhereClauses'], true) : [];
$bindings = isset($_POST['countBindings']) ? json_decode($_POST['countBindings'], true) : [];
$types = $_POST['countTypes'] ?? '';

$total_actual_cost = 0;
$total_items = 0;
$total_records = 0;
$execution_time = 0;

$start_time = microtime(true);

$countQuery = "SELECT COUNT(*) AS total_count, SUM(ACTUAL_COST) AS total_cost, SUM(ITEMS) AS total_items FROM temp_epd";

if (!empty($countWhereClauses)) {
    $whereConditions = [];
    foreach ($countWhereClauses as $clause) {
        $whereConditions[] = mysqli_real_escape_string($conn, $clause);
    }
    $countQuery .= ' WHERE ' . implode(' AND ', $whereConditions);
}

$countStmt = mysqli_prepare($conn, $countQuery);
if ($countStmt) {
    if (!empty($bindings) && !empty($types)) {
        mysqli_stmt_bind_param($countStmt, $types, ...$bindings);
    }

    mysqli_stmt_execute($countStmt);

    $countResult = mysqli_stmt_get_result($countStmt);
    if ($countResult) {
        $countRow = mysqli_fetch_assoc($countResult);
        $total_records = $countRow['total_count'];
        $total_actual_cost = number_format($countRow['total_cost'], 2, '.', ',');
        $total_items = number_format($countRow['total_items'], 0, '.', ',');
    }

    $end_time = microtime(true);
    $execution_time = round(($end_time - $start_time) * 1000, 2);
}

$totals = [
    'total_cost' => $total_actual_cost,
    'total_items' => $total_items,
    'total_records' => $total_records,
    'execution_time_ms' => $execution_time
];

header('Content-Type: application/json');
echo json_encode($totals);
