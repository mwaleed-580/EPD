function sortItems(tableHeader, dataType, functionName) {
  const tableHeaderCols = document.querySelectorAll(tableHeader);
  tableHeaderCols.forEach((item) => {
    item.addEventListener("click", () => {
      tableHeaderCols.forEach((innerItem) => {
        if (innerItem !== item) {
          innerItem.classList.remove("headerAsc");
          innerItem.classList.remove("headerDesc");
        }
      });

      let orderBy = item.getAttribute("data-cols");
      let order = "asc";

      if (item.classList.contains("headerAsc")) {
        item.classList.add("headerDesc");
        item.classList.remove("headerAsc");
        order = "desc";
      } else if (item.classList.contains("headerDesc")) {
        item.classList.remove("headerDesc");
        order = "null";
        orderBy = "null";
      } else {
        item.classList.add("headerAsc");
      }

      dataType.orderByValue = orderBy;
      dataType.orderValue = order;

      functionName(1, orderBy, order);
    });
  });
}

sortItems(
  "#practiceDataTable .tablesorter-header",
  practiceOrder,
  getPracticeResults
);
sortItems(
  "#dispensarDataTable .tablesorter-header",
  dispensarOrder,
  getDispensarResults
);
