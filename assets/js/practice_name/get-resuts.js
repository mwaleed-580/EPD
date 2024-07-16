function getResults(page) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "includes/practice_name/get_practice_data.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      console.log(response);
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
        response.result_obj.forEach(function (item, index) {
          var row = tableBody.insertRow();
          var cell1 = row.insertCell(0);
          var cell2 = row.insertCell(1);
          var cell3 = row.insertCell(2);
          cell1.textContent = index + 1;
          cell2.textContent = item.BNF_DESCRIPTION;
          cell3.textContent = item.TOTAL_ITEMS;
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
      encodeURIComponent(page)
  );
}

document.addEventListener("DOMContentLoaded", getResults(1));
