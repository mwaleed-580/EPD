document.addEventListener("DOMContentLoaded", function () {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "includes/get_total.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      //   console.log(response);
      var totalCost = response.total_cost;
      var totalItems = response.total_items;
      //   var totalRecords = response.total_records;
      document.getElementById("total-cost").textContent = totalCost;
      document.getElementById("total-items").textContent = totalItems;
    } else {
      console.error("AJAX Error: " + xhr.status);
    }
  };
  xhr.onerror = function () {
    console.error("AJAX Error");
  };
  xhr.send(
    "countWhereClauses=" +
      encodeURIComponent(countWhereClauses) +
      "&countBindings=" +
      encodeURIComponent(countBindings) +
      "&countTypes=" +
      encodeURIComponent(countTypes)
  );
});
