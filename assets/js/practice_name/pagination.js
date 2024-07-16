function createPageItem(
  page,
  isActive = false,
  isDisabled = false,
  text = null
) {
  const li = document.createElement("li");
  li.className =
    "page-item" + (isActive ? " active" : "") + (isDisabled ? " disabled" : "");
  li.innerHTML = `<a class="page-link" href="#" onclick="getResults(${page})">${
    text || page
  }</a>`;
  return li;
}

function createPagination() {
  const pagination = document.querySelector("#pagination");

  pagination.innerHTML = "";

  if (currentPage > 1) {
    const prevLi = createPageItem(currentPage - 1, false, false, "Previous");
    pagination.appendChild(prevLi);
  }

  if (currentPage > 3) {
    const firstLi = createPageItem(1);
    pagination.appendChild(firstLi);
    if (currentPage > 4) {
      const ellipsisLi = createPageItem(null, false, true, "...");
      pagination.appendChild(ellipsisLi);
    }
  }

  for (
    let i = Math.max(1, currentPage - 2);
    i <= Math.min(totalPages, currentPage + 2);
    i++
  ) {
    const li = createPageItem(i, i === currentPage);
    pagination.appendChild(li);
  }

  if (currentPage < totalPages - 2) {
    if (currentPage < totalPages - 3) {
      const ellipsisLi = createPageItem(null, false, true, "...");
      pagination.appendChild(ellipsisLi);
    }
    const lastLi = createPageItem(totalPages);
    pagination.appendChild(lastLi);
  }

  if (currentPage < totalPages) {
    const nextLi = createPageItem(currentPage + 1, false, false, "Next");
    pagination.appendChild(nextLi);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "includes/practice_name/get_total_rows.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      console.log(response);
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
