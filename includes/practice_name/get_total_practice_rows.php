<?php
require "../../auth/config.php";

$practice_code = $_POST['practiceCode'];

$start_time = microtime(true);

$query = "SELECT COUNT(*) AS total_results FROM (SELECT BNF_DESCRIPTION, SUM(ITEMS) AS TOTAL_ITEMS FROM EPD__202403 WHERE PRACTICE_CODE = '" . $practice_code . "' GROUP BY BNF_DESCRIPTION ORDER BY BNF_DESCRIPTION) AS subquery";

$result = mysqli_query($conn, $query);
$row_total = mysqli_fetch_assoc($result);
$total_results = $row_total['total_results'];
$total_pages = ceil($total_results / 100);

$end_time = microtime(true);
$execution_time = round(($end_time - $start_time) * 1000, 2);

$totals_response = [
    'total_pages' => $total_pages,
    'execution_time' => $execution_time
];

header('Content-Type: application/json');
echo json_encode($totals_response);
