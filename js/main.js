document.addEventListener(
  "DOMContentLoaded",
  () => {
    //marking tds that edited
    let priceTds = document.querySelectorAll(
      "#products-table > tbody > tr > td:nth-child(4)"
    );
    priceTds.forEach((td) => {
      td.addEventListener("input", (e) => {
        e.target.dataset.modified = "1";
      });
    });

    //select all text on focus
    priceTds.forEach((td) => {
      td.addEventListener("focus", (e) => {
        const el = e.target;
        const range = document.createRange();
        range.selectNodeContents(el);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
      });
    });

    //forbiding using keys other than number to edit
    priceTds.forEach((td) => {
      td.addEventListener("keypress", (e) => {
        if (isNaN(String.fromCharCode(e.which))) e.preventDefault();
      });
    });

    //sending edited tds to update.php
    let updateProductsBtn = document.getElementById("update-products-btn");

    updateProductsBtn.addEventListener("click", () => {
      let editedPriceTds = Array.from(
        document.querySelectorAll(
          "#products-table > tbody > tr > td:nth-child(4)"
        )
      ).filter((td) => td.dataset.modified === "1");

      //reseting page
      resetAlert();
      resetPticeTds();

      if (editedPriceTds.length === 0) {
        alertResult("لطفا قیمت محصولی را عوض کنید", "warning");
      } else {
        let data = {
          update: [],
        };
        for (const td of editedPriceTds) {
          data.update.push({
            id: Number(td.dataset.productId),
            regular_price: Number(td.innerText),
          });
        }
        axios
          .post("update.php", data)
          .then(function (response) {
            if (response.status === 200) {
              alertResult("آپدیت با موفقیت انجام شد", "success");
            } else {
              alertResult(
                response.status + " : خطا در برقراری ارتباط کد",
                "failure"
              );
            }
          })
          .catch(function (error) {
            alertResult(error + " : خطا", "failure");
          });
      }
    });

    updateProductsBtn.addEventListener("focusout", resetAlert);
  },
  false
);

function resetPticeTds() {
  let priceTds = document.querySelectorAll(
    "#products-table > tbody > tr > td:nth-child(4)"
  );
  priceTds.forEach((td) => {
    td.dataset.modified = "0";
  });
}
function resetAlert() {
  let resultDiv = document.getElementById("update-products-result");
  resultDiv.classList.remove("alert-success", "alert-danger", "alert-warning");
  resultDiv.innerHTML = "";
}
function alertResult(message, status) {
  let resultDiv = document.getElementById("update-products-result");
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
