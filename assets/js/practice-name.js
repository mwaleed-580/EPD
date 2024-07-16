document.addEventListener("DOMContentLoaded", function () {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "includes/get_practice_data.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      var tableBody = document
        .getElementById("resultsTable")
        .getElementsByTagName("tbody")[0];
      tableBody.innerHTML = "";

      if (response.result_obj.length === 0) {
        var row = tableBody.insertRow();
        var cell = row.insertCell(0);
        cell.colSpan = 2;
        cell.textContent = "No results found";
      } else {
        response.result_obj.forEach(function (item) {
          var row = tableBody.insertRow();
          var cell1 = row.insertCell(0);
          var cell2 = row.insertCell(1);
          cell1.textContent = item.BNF_DESCRIPTION;
          cell2.textContent = item.TOTAL_ITEMS;
        });
      }
    } else {
      console.error("AJAX Error: " + xhr.status);
    }
  };
  xhr.onerror = function () {
    console.error("AJAX Error");
  };
  xhr.send("practiceCode=" + encodeURIComponent(practiceCode));
});
