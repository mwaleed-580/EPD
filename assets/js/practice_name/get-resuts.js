function getResults(page, orderBy = null, order = null) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "includes/practice_name/get_practice_data.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      var tableBody = document
        .getElementById("resultsTable")
        .getElementsByTagName("tbody")[0];
      tableBody.innerHTML = "";
      currentPage = page;

      if (create_pagination) {
        createPagination();
      }
      create_pagination = true;

      if (response.result_obj.length === 0) {
        var row = tableBody.insertRow();
        var cell = row.insertCell(0);
        cell.colSpan = 3;
        cell.textContent = "No results found";
      } else {
        let srNo = currentPage;
        if (currentPage > 1) {
          srNo = (currentPage - 1) * 100 + 1;
        }

        response.result_obj.forEach(function (item) {
          var row = tableBody.insertRow();
          var cell1 = row.insertCell(0);
          var cell2 = row.insertCell(1);
          var cell3 = row.insertCell(2);
          var cell4 = row.insertCell(3);

          cell1.textContent = srNo;
          cell2.textContent = item.BNF_DESCRIPTION;
          cell3.textContent = item.TOTAL_ITEMS;

          var totalCost = item.TOTAL_COST;
          totalCost = parseFloat(totalCost);
          let formattedTotalCost = totalCost.toLocaleString("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          });
          cell4.textContent = "£" + formattedTotalCost;
          srNo++;
        });
      }
    } else {
      console.error("AJAX Error: " + xhr.status);
    }
  };
  xhr.onerror = function () {
    console.error("AJAX Error");
  };
  xhr.send(
    "practiceCode=" +
      encodeURIComponent(practiceCode) +
      "&page=" +
      encodeURIComponent(page) +
      "&orderBy=" +
      encodeURIComponent(orderBy) +
      "&order=" +
      encodeURIComponent(order)
  );
}

document.addEventListener("DOMContentLoaded", getResults(1));
