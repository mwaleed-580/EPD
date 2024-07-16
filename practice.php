<?php require "auth/config.php"; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $practice_code = $_GET['practice-code'];

    $query = "SELECT PRACTICE_NAME FROM temp_epd WHERE PRACTICE_CODE = '" . $practice_code . "' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['PRACTICE_NAME'];
    } else {
        die;
    }

    $query = "SELECT SUM(ITEMS) AS TOTAL_ITEMS FROM temp_epd WHERE PRACTICE_CODE = '" . $practice_code . "'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $total_items = $row['TOTAL_ITEMS'];
}
?>

<?php require "templates/header.php"; ?>

<div class="main-wrapper">
    <h1 class="my-4"><?php echo $title; ?></h1>
    <p> <strong>Total Items:</strong> <span id="total-items"><?php echo $total_items; ?></span> </p>

    <table class="table my-4" id="resultsTable">
        <thead>
            <tr>
                <th scope="col">Sr no.</th>
                <th scope="col">BNF Description</th>
                <th scope="col">Total Items</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center" id="pagination">
        </ul>
    </nav>

</div>

<script>
    const practiceCode = <?php echo json_encode($practice_code); ?>;
    var last_BNF_Description = null;
    var currentPage = 1;
    var totalPages;
    var create_pagination = false;
</script>
<script src="assets/js/practice_name/get-resuts.js"></script>
<script src="assets/js/practice_name/pagination.js"></script>

<?php require "templates/footer.php"; ?>