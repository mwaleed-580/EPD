document.addEventListener("DOMContentLoaded", function () {
  const orderBy = document.getElementById("order-by");
  const order = document.getElementById("order");
  const sortBtn = document.getElementById("sort");

  orderByValue = orderBy.value;
  orderValue = order.value;

  orderBy.addEventListener("change", () => {
    orderByValue = orderBy.value;

    if (orderByValue !== "null" && orderValue !== "null") {
      sortBtn.disabled = false;
    } else {
      sortBtn.disabled = true;
    }
  });

  order.addEventListener("change", () => {
    orderValue = order.value;
    if (orderByValue !== "null" && orderValue !== "null") {
      sortBtn.disabled = false;
    } else {
      sortBtn.disabled = true;
    }
  });

  sortBtn.addEventListener("click", () => {
    orderByValue = orderBy.value;
    orderValue = order.value;
    getResults(1, orderByValue, orderValue);
  });
});
