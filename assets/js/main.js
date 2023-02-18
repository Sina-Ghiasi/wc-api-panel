document.addEventListener(
  "DOMContentLoaded",
  () => {
    let priceTds = document.querySelectorAll(
      "#products-table > tbody > tr > td:nth-child(4)"
    );
    let priceComponentTds = document.querySelectorAll(
      "#products-table > tbody > tr > td:nth-child(3)"
    );

    //marking tds that edited
    addModificationListener(priceTds);
    addModificationListener(priceComponentTds);

    addSelectAllTextOnFocus(priceTds);
    addSelectAllTextOnFocus(priceComponentTds);

    forbidEnteringNonNumericKey(priceTds);
    forbidEnteringNonNumericKey(priceComponentTds);

    //sending edited tds to update_product_by_list.php
    let updateProductsByList = document.getElementById(
      "update-products-by-list"
    );

    updateProductsByList.addEventListener("click", () => {
      //reseting page

      let editedTrs = Array.from(
        document.querySelectorAll("#products-table > tbody > tr")
      ).filter((tr) => tr.dataset.modified === "1");

      resetAlert("update-products-result");
      resetEditedTrs();

      if (editedTrs.length === 0) {
        alertResult(
          "لطفا قیمت محصولی را عوض کنید",
          "warning",
          "update-products-result"
        );
      } else {
        let data = [];
        for (const tr of editedTrs) {
          data.push({
            id: Number(tr.dataset.productId),
            regular_price: tr.children[3].innerText,
            meta_data: [
              {
                key: "wpc_product_price_component",
                value: tr.children[2].innerText,
              },
            ],
          });
        }
        axios
          .post("update_product_by_list.php", data)
          .then(function (response) {
            if (response.status === 200) {
              alertResult(
                "آپدیت با موفقیت انجام شد",
                "success",
                "update-products-result"
              );
            } else {
              alertResult(
                response.status + " : خطا در برقراری ارتباط کد",
                "failure",
                "update-products-result"
              );
            }
          })
          .catch(function (error) {
            alertResult(error + " : خطا", "failure", "update-products-result");
          });
      }
    });

    updateProductsByList.addEventListener("focusout", () => {
      resetAlert("update-products-result");
    });

    //filter button
    let filterProductsBtn = document.getElementById("filter-products-btn");
    filterProductsBtn.addEventListener("click", () => {
      const params = new URLSearchParams();
      let selectedCategory = document.getElementById(
        "products-category-select"
      ).value;
      if (selectedCategory && selectedCategory !== "all")
        params.append("category_id", selectedCategory);

      let searchInputText = document.getElementById(
        "products-search-input"
      ).value;
      if (searchInputText) params.append("search_str", searchInputText);

      if (params.toString() === "") {
        location.href = window.location.origin + window.location.pathname;
      } else {
        location.href =
          location.origin + location.pathname + "?" + params.toString();
      }
    });

    //price bulk edit modal

    let modalCategorySelect = document.getElementById("modal-category-select");
    let wireRod = document.getElementById("wir-rod");
    let profitValue = document.getElementById("profit-value");
    let updateCategoryValues = document.getElementById(
      "update-category-values"
    );
    //initial model inputs
    setModalInputsValues(modalCategorySelect.value, { wireRod, profitValue });

    modalCategorySelect.addEventListener("change", () => {
      resetAlert("modal-update-result");
      setModalInputsValues(modalCategorySelect.value, { wireRod, profitValue });
    });

    updateCategoryValues.addEventListener("click", () => {
      resetAlert("modal-update-result");
      setCategoryValues(modalCategorySelect, { wireRod, profitValue });
    });
    updateCategoryValues.addEventListener("focusout", () => {
      resetAlert("modal-update-result");
    });

    let updateProductsByCategory = document.getElementById(
      "update-products-by-category"
    );
    updateProductsByCategory.addEventListener("click", () => {
      setCategoryValues(modalCategorySelect, { wireRod, profitValue });

      let data = {
        id: modalCategorySelect.value,
        name: modalCategorySelect.options[modalCategorySelect.selectedIndex]
          .text,
        wire_rod: Number(wireRod.value),
        profit: Number(profitValue.value),
      };
      axios
        .post("update_products_by_category.php", data)
        .then(function (response) {
          if (response.status === 200) {
            resetAlert("modal-update-result");
            alertResult(
              "آپدیت محصولات بر اساس دسته بندی با موفقیت انجام شد",
              "success",
              "modal-update-result"
            );
            location.href =
              window.location.origin +
              window.location.pathname +
              "?category_id=" +
              modalCategorySelect.value;
          } else {
            alertResult(
              response.status + " : خطا در برقراری ارتباط کد",
              "failure",
              "modal-update-result"
            );
          }
        })
        .catch(function (error) {
          alertResult(error + " : خطا", "failure", "modal-update-result");
        });
    });
    //end
  },
  false
);

function resetEditedTrs() {
  let priceTds = document.querySelectorAll("#products-table > tbody > tr");
  priceTds.forEach((tr) => {
    tr.dataset.modified = "0";
  });
}
function resetAlert(id) {
  let resultDiv = document.getElementById(id);
  resultDiv.classList.remove("alert-success", "alert-danger", "alert-warning");

  resultDiv.innerHTML = "";
}
function alertResult(message, status, id) {
  let resultDiv = document.getElementById(id);
  const resultIcon = document.createElement("i");
  const resultMessage = document.createElement("span");

  if (status === "success") {
    resultDiv.classList.add("alert-success");
    resultIcon.classList.add("bi", "bi-check-circle-fill");
  } else if (status === "failure") {
    resultDiv.classList.add("alert-danger");
    resultIcon.classList.add("bi", "bi-exclamation-triangle-fill");
  } else if (status === "warning") {
    resultDiv.classList.add("alert-warning");
    resultIcon.classList.add("bi", "bi-exclamation-triangle-fill");
  }
  resultMessage.classList.add("ps-3");
  resultMessage.innerText = message;

  resultDiv.appendChild(resultIcon);
  resultDiv.appendChild(resultMessage);
}

function addModificationListener(array) {
  array.forEach((td) => {
    td.addEventListener("input", (e) => {
      e.target.parentNode.dataset.modified = "1";
    });
  });
}

function addSelectAllTextOnFocus(array) {
  array.forEach((td) => {
    td.addEventListener("focus", (e) => {
      const el = e.target;
      const range = document.createRange();
      range.selectNodeContents(el);
      const sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(range);
    });
  });
}

function forbidEnteringNonNumericKey(array) {
  array.forEach((td) => {
    td.addEventListener("keypress", (e) => {
      if (isNaN(String.fromCharCode(e.which))) e.preventDefault();
    });
  });
}
function setModalInputsValues(category_id, inputs) {
  axios
    .get("database/get.php", {
      params: { category_id },
    })
    .then(function (response) {
      if (response.status === 200) {
        if (response.data) {
          inputs.wireRod.value = response.data.wire_rod;
          inputs.profitValue.value = response.data.profit;
        } else {
          inputs.wireRod.value = "";
          inputs.profitValue.value = "";
          alertResult(
            "مقادیری برای این دسته بندی تعیین نشده است",
            "warning",
            "modal-update-result"
          );
        }
      } else {
        alertResult(
          response.status + " : خطا در برقراری ارتباط کد",
          "failure",
          "modal-update-result"
        );
      }
    })
    .catch(function (error) {
      alertResult(error + " : خطا", "failure", "modal-update-result");
    });
}
function setCategoryValues(select, inputs) {
  let data = {
    id: select.value,
    name: select.options[select.selectedIndex].text,
    wire_rod: Number(inputs.wireRod.value),
    profit: Number(inputs.profitValue.value),
  };
  axios
    .post("database/insert_or_update.php", data)
    .then(function (response) {
      if (response.status === 200) {
        alertResult(
          "آپدیت مقادیر دسته بندی با موفقیت انجام شد",
          "success",
          "modal-update-result"
        );
      } else {
        alertResult(
          response.status + " : خطا در برقراری ارتباط کد",
          "failure",
          "modal-update-result"
        );
      }
    })
    .catch(function (error) {
      alertResult(error + " : خطا", "failure", "modal-update-result");
    });
}
