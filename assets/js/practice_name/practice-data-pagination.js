function createPageItem(
  page,
  isActive = false,
  isDisabled = false,
  text = null
) {
  const li = document.createElement("li");
  li.className =
    "page-item" + (isActive ? " active" : "") + (isDisabled ? " disabled" : "");
  li.innerHTML = `<a class="page-link" href="#" onclick="getPracticeResults(${page},'${
    practiceOrder.orderByValue
  }','${practiceOrder.orderValue}')">${text || page}</a>`;
  return li;
}

function createPagination() {
  const pagination = document.querySelector("#pagination");

  pagination.innerHTML = "";

  if (practiceOrder.currentPage > 1) {
    const prevLi = createPageItem(
      practiceOrder.currentPage - 1,
      false,
      false,
      "Previous"
    );
    pagination.appendChild(prevLi);
  }

  if (practiceOrder.currentPage > 3) {
    const firstLi = createPageItem(1);
    pagination.appendChild(firstLi);
    if (practiceOrder.currentPage > 4) {
      const ellipsisLi = createPageItem(null, false, true, "...");
      pagination.appendChild(ellipsisLi);
    }
  }

  for (
    let i = Math.max(1, practiceOrder.currentPage - 2);
    i <= Math.min(totalPages, practiceOrder.currentPage + 2);
    i++
  ) {
    const li = createPageItem(i, i === practiceOrder.currentPage);
    pagination.appendChild(li);
  }

  if (practiceOrder.currentPage < totalPages - 2) {
    if (practiceOrder.currentPage < totalPages - 3) {
      const ellipsisLi = createPageItem(null, false, true, "...");
      pagination.appendChild(ellipsisLi);
    }
    const lastLi = createPageItem(totalPages);
    pagination.appendChild(lastLi);
  }

  if (practiceOrder.currentPage < totalPages) {
    const nextLi = createPageItem(
      practiceOrder.currentPage + 1,
      false,
      false,
      "Next"
    );
    pagination.appendChild(nextLi);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "includes/practice_name/get_total_practice_rows.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      totalPages = response.total_pages;
      createPagination();
    } else {
      console.error("AJAX Error: " + xhr.status);
    }
  };
  xhr.onerror = function () {
    console.error("AJAX Error");
  };
  xhr.send("practiceCode=" + encodeURIComponent(practiceCode));
});
