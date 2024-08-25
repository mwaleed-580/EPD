<?php require "auth/config.php"; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $practice_code = $_GET['practice-code'];

    $query = "SELECT PRACTICE_NAME FROM EPD__202403 WHERE PRACTICE_CODE = '" . $practice_code . "' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['PRACTICE_NAME'];
    } else {
        die;
    }

    $query = "SELECT SUM(ITEMS) AS TOTAL_ITEMS, SUM(ACTUAL_COST) AS TOTAL_COST FROM EPD__202403 WHERE PRACTICE_CODE = '" . $practice_code . "'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $total_items = $row['TOTAL_ITEMS'];
    $total_cost = $row['TOTAL_COST'];
}
?>

<?php require "templates/header.php"; ?>

<div class="main-wrapper q">
    <h1 class="my-4"><?php echo $title; ?></h1>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-2 border border-primary rounded-circle me-3">
                                <div class="icon-box md bg-primary-fade rounded-5">
                                    <i class="ri-luggage-cart-fill fs-4 color-primary"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <h4>Total Overall Items: </h4>
                                <p class="m-0"><?php echo number_format($total_items, 0, '.', ','); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-2 border border-primary rounded-circle me-3">
                                <div class="icon-box md bg-primary-fade rounded-5">
                                    <i class="ri-money-dollar-circle-line fs-4 color-primary"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <h4>Total Overall Cost: </h4>
                                <p class="m-0">£<?php echo number_format($total_cost, 2, '.', ','); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="my-4 py-4">All Items Sold By <?php echo $title; ?></h2>

    <div class="card">
        <div class="card-body">
            <table class="table align-middle table-hover" id="practiceDataTable">
                <thead>
                    <tr>
                        <th scope="col" class="width-100">Sr no.</th>
                        <th scope="col" class="tablesorter-header" data-cols="BNF_DESCRIPTION">BNF Description</th>
                        <th scope="col" class="width-150 tablesorter-header" data-cols="TOTAL_ITEMS">Total Items</th>
                        <th scope="col" class="width-150 tablesorter-header" data-cols="TOTAL_COST">Total Cost</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center" id="pagination">
                </ul>
            </nav>

        </div>
    </div>

    <h2 class="my-4 py-4">All Dispensars Assocatied With <?php echo $title; ?></h2>

    <div class="card">
        <div class="card-body">
            <table class="table align-middle table-hover" id="dispensarDataTable">
                <thead>
                    <tr>
                        <th scope="col" class="width-100">Sr no.</th>
                        <th scope="col" class="tablesorter-header" data-cols="DispenserName">Dispensar Name</th>
                        <th scope="col" class="width-150 tablesorter-header" data-cols="TOTAL_ITEMS">Total Items</th>
                        <th scope="col" class="width-150 tablesorter-header" data-cols="TOTAL_EPS_ITEMS">Total EPS Items</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center" id="pagination">
                </ul>
            </nav>
        </div>
    </div>

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
<script src="assets/js/practice_name/get-practice-data.js"></script>
<script src="assets/js/practice_name/practice-data-pagination.js"></script>
<script>
    practiceOrder = {
        currentPage: 1,
        orderByValue: "null",
        orderValue: "null"
    };
    dispensarOrder = {
        currentPage: 1,
        orderByValue: "null",
        orderValue: "null"
    };
</script>
<script src="assets/js/practice_name/get-dispensar-data.js"></script>
<!-- <script src="assets/js/practice_name/dispensar-data-pagination.js"></script> -->
<script src="assets/js/practice_name/sort-items.js"></script>

<?php require "templates/footer.php"; ?>