function getURL(orderBy, order) {
  let currentURL = new URL(window.location.href);
  let params = new URLSearchParams(currentURL.search);

  params.delete("orderby");
  params.delete("order");

  if (orderBy && order) {
    params.set("orderby", orderBy);
    params.set("order", order);
  }

  currentURL.search = params.toString();
  window.location.href = currentURL.toString();
}

const tableHeaderCols = document.querySelectorAll(".tablesorter-header");
tableHeaderCols.forEach((item) => {
  item.addEventListener("click", () => {
    let orderBy = item.getAttribute("data-cols");
    let order = "ASC";
    if (item.classList.contains("headerAsc")) {
      order = "DESC";
    } else if (item.classList.contains("headerDesc")) {
      order = "";
      orderBy = "";
    }
    getURL(orderBy, order);
  });
});
