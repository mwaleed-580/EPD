document.addEventListener("DOMContentLoaded", function () {
  document.querySelector(".add-filter").addEventListener("click", function () {
    var index = document.querySelectorAll("#filter-rows .filter-row").length;
    var newRow =
      '<div class="row g-3 filter-row my-1">' +
      '<div class="col-md-4">' +
      '<select name="filters[' +
      index +
      '][field]" class="form-control">' +
      '<option value="items">Items</option>' +
      '<option value="nic">NIC</option>' +
      '<option value="actual_cost">Actual cost</option>' +
      '<option value="quantity">Quantity</option>' +
      '<option value="total_quantity">Total Quantity</option>' +
      "</select>" +
      "</div>" +
      '<div class="col-md-2">' +
      '<select name="filters[' +
      index +
      '][operator]" class="form-control">' +
      '<option value="greater">Greater than</option>' +
      '<option value="less">Less than</option>' +
      "</select>" +
      "</div>" +
      '<div class="col-md-4">' +
      '<input type="number" name="filters[' +
      index +
      '][value]" class="form-control" placeholder="Value">' +
      "</div>" +
      '<div class="col-md-2">' +
      '<button type="button" class="btn btn-danger remove-filter">Remove</button>' +
      "</div>" +
      "</div>";
    document
      .getElementById("filter-rows")
      .insertAdjacentHTML("beforeend", newRow);
  });

  document.addEventListener("click", function (event) {
    if (event.target.classList.contains("remove-filter")) {
      event.target.closest(".filter-row").remove();
    }
  });
});
