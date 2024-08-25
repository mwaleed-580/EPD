function getDispensarResults(page, orderBy = null, order = null) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "includes/practice_name/get_dispensar_data.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      var tableBody = document
        .getElementById("dispensarDataTable")
        .getElementsByTagName("tbody")[0];
      tableBody.innerHTML = "";
      dispensarOrder.currentPage = page;

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
        let srNo = dispensarOrder.currentPage;
        if (dispensarOrder.currentPage > 1) {
          srNo = (dispensarOrder.currentPage - 1) * 100 + 1;
        }

        response.result_obj.forEach(function (item) {
          var row = tableBody.insertRow();
          var cell1 = row.insertCell(0);
          var cell2 = row.insertCell(1);
          var cell3 = row.insertCell(2);
          var cell4 = row.insertCell(3);

          cell1.textContent = srNo;
          cell2.textContent = item.DispenserName;
          cell3.textContent = item.TOTAL_ITEMS;
          cell4.textContent = item.TOTAL_EPS_ITEMS;
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

document.addEventListener("DOMContentLoaded", getDispensarResults(1));
