<?php
require "../auth/config.php";

$practice_code = $_POST['practiceCode'];
$start_time = microtime(true);

$query = "SELECT BNF_DESCRIPTION, SUM(ITEMS) AS TOTAL_ITEMS
FROM temp_epd
WHERE PRACTICE_CODE = '" . $practice_code . "'
GROUP BY BNF_DESCRIPTION
ORDER BY BNF_DESCRIPTION
LIMIT 100";

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
