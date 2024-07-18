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

    $query = "SELECT SUM(ITEMS) AS TOTAL_ITEMS, SUM(ACTUAL_COST) AS TOTAL_COST FROM temp_epd WHERE PRACTICE_CODE = '" . $practice_code . "'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $total_items = $row['TOTAL_ITEMS'];
    $total_cost = $row['TOTAL_COST'];
}
?>

<?php require "templates/header.php"; ?>

<div class="main-wrapper">
    <h1 class="my-4"><?php echo $title; ?></h1>
    <p> <strong>Total Overall Items:</strong> <span id="total-items"><?php echo number_format($total_items, 0, '.', ','); ?></span> </p>
    <p> <strong>Total Overall Cost:</strong> <span id="total-items"><?php echo number_format($total_cost, 2, '.', ','); ?></span> </p>

    <div id="filter-rows" class="mt-3">
        <div class="row g-3 filter-row my-1">
            <div class="col-md-4">
                <select class="form-control" id="order-by">
                    <option disabled selected value="null">Order By</option>
                    <option value="TOTAL_ITEMS">Items</option>
                    <option value="BNF_DESCRIPTION">BNF Description</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" id="order">
                    <option disabled selected value="null">Order</option>
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-success" id="sort" disabled>Sort Results</button>
            </div>
        </div>
    </div>

    <table class="table my-4" id="resultsTable">
        <thead>
            <tr>
                <th scope="col">Sr no.</th>
                <th scope="col">BNF Description</th>
                <th scope="col">Total Items</th>
                <th scope="col">Total Cost</th>
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
    var orderByValue;
    var orderValue;
</script>
<script src="assets/js/practice_name/get-resuts.js"></script>
<script src="assets/js/practice_name/pagination.js"></script>
<script src="assets/js/practice_name/sort-items.js"></script>

<?php require "templates/footer.php"; ?>