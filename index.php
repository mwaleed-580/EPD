<?php $title = "NHS BSA Database 2024" ?>

<?php require "templates/header.php"; ?>

<?php

$itemsPerPage = 100;
$lastId = isset($_GET['page']) ? intval($_GET['page']) : 0;

$query = "SELECT * FROM EPD__202403";
$whereClauses = [];
$bindings = [];
$types = '';

$filters = isset($_GET['filters']) ? $_GET['filters'] : [];

if (!empty($_GET['search'])) {
    $search = '%' . mysqli_real_escape_string($conn, $_GET['search']) . '%';
    $whereClauses[] = "(BNF_CHAPTER_PLUS_CODE LIKE ? OR BNF_DESCRIPTION LIKE ? OR PRACTICE_NAME LIKE ?)";
    $bindings[] = $search;
    $bindings[] = $search;
    $bindings[] = $search;
    $types .= 'sss';
}

if (!empty($filters)) {
    foreach ($filters as $filter) {
        $field = mysqli_real_escape_string($conn, $filter['field']);
        $operator = mysqli_real_escape_string($conn, $filter['operator']);
        $value = mysqli_real_escape_string($conn, $filter['value']);

        if ($operator == 'greater') {
            $whereClauses[] = "$field > ?";
            $types .= 's';
        } elseif ($operator == 'less') {
            $whereClauses[] = "$field < ?";
            $types .= 's';
        }
        $bindings[] = $value;
    }
}

$countWhereClauses = $whereClauses;
$countBindings = $bindings;
$countTypes =  $types;

if ($lastId > 0) {
    $whereClauses[] = "id > ?";
    $bindings[] = $lastId;
    $types .= 'i';
}

if (!empty($whereClauses)) {
    $query .= ' WHERE ' . implode(' AND ', $whereClauses);
}

$orderBy = null;
$order = null;

if (isset($_GET['orderby']) && isset($_GET['order']) && $_GET['orderby']) {
    $orderBy = mysqli_real_escape_string($conn, $_GET['orderby']);
    $order = mysqli_real_escape_string($conn, $_GET['order']);
    $query .= " ORDER BY $orderBy $order";
} else {
    $query .= " ORDER BY id ASC";
}

$query .= " LIMIT ?";
$bindings[] = $itemsPerPage;
$types .= 'i';

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, $types, ...$bindings);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$nextLastId = 0;

function getHeaderClass($dataCols, $orderBy, $order)
{
    if ($dataCols === $orderBy) {
        return $order === 'ASC' ? 'headerAsc' : 'headerDesc';
    }
    return '';
}

?>

<div class="main-wrapper">
    <h1 class="my-4">NHS BSA Database April 2024</h1>

    <form method="GET" action="<?php $_SERVER['PHP_SELF'] ?>" class="mb-4">
        <div class="row g-12 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by chapter code or description" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            </div>
            <div class="col-md-6">
                <p class="my-0"><strong>Total Cost: </strong>£<span id="total-cost">0</span></p>
                <p class="my-0"><strong>Total Items: </strong><span id="total-items">0</span></p>
            </div>
        </div>
        <div id="filter-rows" class="mt-3">
            <?php
            if (!empty($filters)) {
                foreach ($filters as $index => $filter) {
                    $field = $filter['field'];
                    $operator = $filter['operator'];
                    $value = $filter['value'];
                    echo '<div class="row g-3 filter-row my-1">
                            <div class="col-md-4">
                                <select name="filters[' . $index . '][field]" class="form-control">
                                    <option value="items"' . ($field == 'items' ? ' selected' : '') . '>Items</option>
                                    <option value="nic"' . ($field == 'nic' ? ' selected' : '') . '>NIC</option>
                                    <option value="actual_cost"' . ($field == 'actual_cost' ? ' selected' : '') . '>Actual cost</option>
                                    <option value="quantity"' . ($field == 'quantity' ? ' selected' : '') . '>Quantity</option>
                                    <option value="total_quantity"' . ($field == 'total_quantity' ? ' selected' : '') . '>Total Quantity</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="filters[' . $index . '][operator]" class="form-control">
                                    <option value="greater"' . ($operator == 'greater' ? ' selected' : '') . '>Greater than</option>
                                    <option value="less"' . ($operator == 'less' ? ' selected' : '') . '>Less than</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="filters[' . $index . '][value]" class="form-control" placeholder="Value" value="' . $value . '">
                            </div>';
                    if ($index == 0) {
                        echo '<div class="col-md-2">
                                <button type="button" class="btn btn-success add-filter">Add Filter</button>
                            </div>';
                    } else {
                        echo '<div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-filter">Remove</button>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<div class="row g-3 filter-row my-1">
                        <div class="col-md-4">
                            <select name="filters[0][field]" class="form-control">
                                <option value="items">Items</option>
                                <option value="nic">NIC</option>
                                <option value="actual_cost">Actual cost</option>
                                <option value="quantity">Quantity</option>
                                <option value="total_quantity">Total Quantity</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="filters[0][operator]" class="form-control">
                                <option value="greater">Greater than</option>
                                <option value="less">Less than</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="filters[0][value]" class="form-control" placeholder="Value">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success add-filter">Add Filter</button>
                        </div>
                    </div>';
            }
            ?>
        </div>
        <div class="row py-3">
            <div class="col-md-3">
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="/php-projects/EPD/" class="btn btn-danger">Reset Filters</a>
                </div>
            </div>
        </div>
    </form>

    <table class="table table-hover my-4">
        <thead>
            <tr>
                <th scope="col">Sr no</th>
                <th class="tablesorter-header <?php echo getHeaderClass('BNF_CODE', $orderBy, $order); ?>" scope="col" data-cols="BNF_CODE">Bnf code</th>
                <th class="tablesorter-header <?php echo getHeaderClass('PRACTICE_NAME', $orderBy, $order); ?>" scope="col" data-cols="PRACTICE_NAME">Practice Name</th>
                <th class="tablesorter-header <?php echo getHeaderClass('BNF_CHAPTER_PLUS_CODE', $orderBy, $order); ?>" scope="col" data-cols="BNF_CHAPTER_PLUS_CODE">Name</th>
                <th class="tablesorter-header <?php echo getHeaderClass('ITEMS', $orderBy, $order); ?>" scope="col" data-cols="ITEMS">Items</th>
                <th class="tablesorter-header <?php echo getHeaderClass('NIC', $orderBy, $order); ?>" scope="col" data-cols="NIC">NIC</th>
                <th class="tablesorter-header <?php echo getHeaderClass('ACTUAL_COST', $orderBy, $order); ?>" scope="col" data-cols="ACTUAL_COST">Actual cost</th>
                <th class="tablesorter-header <?php echo getHeaderClass('QUANTITY', $orderBy, $order); ?>" scope="col" data-cols="QUANTITY">Quantity</th>
                <th class="tablesorter-header <?php echo getHeaderClass('TOTAL_QUANTITY', $orderBy, $order); ?>" scope="col" data-cols="TOTAL_QUANTITY">Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = $lastId + 1;
            $totals = [
                'ITEMS' => 0,
                'NIC' => 0,
                'ACTUAL_COST' => 0,
                'QUANTITY' => 0,
                'TOTAL_QUANTITY' => 0,
            ];
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <th scope="row">' . $count . '</th>
                        <td>' . htmlspecialchars($row['BNF_CODE']) . '</td>
                        <td><a href="practice.php?practice-code=' . htmlspecialchars($row['PRACTICE_CODE']) . '">' . htmlspecialchars($row['PRACTICE_NAME']) . '</a></td>
                        <td>' . htmlspecialchars($row['BNF_CHAPTER_PLUS_CODE'] . ' ' . $row['BNF_DESCRIPTION']) . '</td>
                        <td>' . number_format($row['ITEMS'], 0, '.', ',') . '</td>
                        <td>£' . number_format($row['NIC'], 2, '.', ',') . '</td>
                        <td>£' . number_format($row['ACTUAL_COST'], 2, '.', ',') . '</td>
                        <td>' . number_format($row['QUANTITY'], 0, '.', ',') . '</td>
                        <td>' . number_format($row['TOTAL_QUANTITY'], 0, '.', ',') . '</td>
                    </tr>';
                $count++;
                $totals['ITEMS'] += $row['ITEMS'];
                $totals['NIC'] += $row['NIC'];
                $totals['ACTUAL_COST'] += $row['ACTUAL_COST'];
                $totals['QUANTITY'] += $row['QUANTITY'];
                $totals['TOTAL_QUANTITY'] += $row['TOTAL_QUANTITY'];
                $nextLastId = $row['id'];
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><strong>Total:</strong></td>
                <td><strong><?php echo number_format($totals['ITEMS'], 0, '.', ','); ?></strong></td>
                <td><strong>£<?php echo number_format($totals['NIC'], 2, '.', ','); ?></strong></td>
                <td><strong>£<?php echo number_format($totals['ACTUAL_COST'], 2, '.', ','); ?></strong></td>
                <td><strong><?php echo number_format($totals['QUANTITY'], 0, '.', ','); ?></strong></td>
                <td><strong><?php echo number_format($totals['TOTAL_QUANTITY'], 0, '.', ','); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    const countWhereClauses = <?php echo json_encode($countWhereClauses); ?>;
    const countBindings = <?php echo json_encode($countBindings); ?>;
    const countTypes = <?php echo json_encode($countTypes); ?>;
</script>
<script src="assets/js/get-totals.js"></script>
<script src="assets/js/filters.js"></script>
<script src="assets/js/sorting.js"></script>

<!-- <?php
        $boundQuery = $query;
        foreach ($bindings as $value) {
            $value = is_string($value) ? "'$value'" : $value;
            $boundQuery = preg_replace('/\?/', $value, $boundQuery, 1);
        }

        echo $boundQuery;

        ?> -->

<?php require "templates/footer.php"; ?>