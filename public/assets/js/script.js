const ERROR_MESSAGES = {
  invalid_credentials: "E-mail ou senha incorretos.",
  missing_fields: "Por favor, preencha todos os campos.",
  default: "Ocorreu um erro inesperado. Tente novamente.",
};

window.toggleSearch = function () {
  const overlay = document.getElementById("mobile-search-overlay");
  const inputMobile = document.getElementById("mobile-search-input");

  if (overlay) {
    if (overlay.classList.contains("d-none")) {
      overlay.classList.remove("d-none");
      if (inputMobile) setTimeout(() => inputMobile.focus(), 100);
    } else {
      overlay.classList.add("d-none");
    }
  }
};

window.switchScreens = function (event) {
  if (event) {
    event.stopPropagation();
    event.preventDefault();
  }

  const loginView = document.getElementById("login-screen");
  const registerView = document.getElementById("register-screen");

  if (loginView && registerView) {
    if (loginView.classList.contains("d-none")) {
      loginView.classList.remove("d-none");
      registerView.classList.add("d-none");
    } else {
      loginView.classList.add("d-none");
      registerView.classList.remove("d-none");
    }
  }
};

window.switchToForgotPassword = function (event) {
  if (event) {
    event.stopPropagation();
    event.preventDefault();
  }

  const loginView = document.getElementById("login-screen");
  const forgotView = document.getElementById("forgot-password-screen");

  if (loginView && forgotView) {
    loginView.classList.add("d-none");
    forgotView.classList.remove("d-none");
  }
};

window.backToLogin = function (event) {
  if (event) {
    event.stopPropagation();
    event.preventDefault();
  }

  const views = [
    document.getElementById("register-screen"),
    document.getElementById("forgot-password-screen"),
    document.getElementById("forgot-password-success-screen"),
  ];
  const loginView = document.getElementById("login-screen");

  views.forEach((view) => {
    if (view) view.classList.add("d-none");
  });

  if (loginView) loginView.classList.remove("d-none");
};

window.togglePassword = function (inputId, btn) {
  const input = document.getElementById(inputId);
  const icon = btn.querySelector("i");

  if (input && icon) {
    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  }
};

window.validateEmail = function (input) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (input.value.length > 0) {
    if (regex.test(input.value)) {
      input.classList.add("is-valid");
      input.classList.remove("is-invalid");
    } else {
      input.classList.add("is-invalid");
      input.classList.remove("is-valid");
    }
  } else {
    input.classList.remove("is-valid", "is-invalid");
  }
};

window.validatePasswordConfirmation = function () {
  const password = document.getElementById("senhaRegister");
  const confirm = document.getElementById("senhaRegisterConf");

  if (password && confirm && confirm.value.length > 0) {
    if (password.value === confirm.value) {
      confirm.classList.remove("is-invalid");
      confirm.classList.add("is-valid");
    } else {
      confirm.classList.remove("is-valid");
      confirm.classList.add("is-invalid");
    }
  } else if (confirm) {
    confirm.classList.remove("is-valid", "is-invalid");
  }
};

window.phoneMask = function (input) {
  let v = input.value.replace(/\D/g, "");
  v = v.substring(0, 11);

  if (v.length > 10) {
    v = v.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
  } else if (v.length > 5) {
    v = v.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
  } else if (v.length > 2) {
    v = v.replace(/^(\d\d)(\d{0,5}).*/, "($1) $2");
  } else {
    if (v.length !== 0) {
      v = v.replace(/^(\d*)/, "($1");
    }
  }
  input.value = v;
};

window.populateEditModal = function (data) {
  if (!data) return;

  const fields = {
    edit_user_id: data.id,
    edit_name: data.name,
    edit_email: data.email,
    edit_role_id: data.role_id,
  };

  for (const [id, value] of Object.entries(fields)) {
    const element = document.getElementById(id);
    if (element) {
      element.value = value;
    }
  }
};

window.formatMoney = function (input) {
  let value = input.value.replace(/\D/g, "");

  if (value === "") {
    input.value = "";
    return;
  }

  value = (parseInt(value) / 100).toLocaleString("pt-BR", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });

  input.value = value;
};

window.populateProductEdit = function (data) {
  if (!data) return;

  const fields = {
    edit_id: data.id,
    edit_name: data.name,
    edit_description: data.description,
    edit_category_id: data.category_id,
    edit_stock_quantity: data.stock_quantity,
  };

  for (const [id, value] of Object.entries(fields)) {
    const element = document.getElementById(id);
    if (element) element.value = value;
  }

  const cashInput = document.getElementById("edit_price_cash");
  const instInput = document.getElementById("edit_price_installments");

  if (cashInput) {
    cashInput.value = parseFloat(data.price_cash).toLocaleString("pt-BR", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  }

  if (instInput) {
    instInput.value = parseFloat(data.price_installments).toLocaleString(
      "pt-BR",
      { minimumFractionDigits: 2, maximumFractionDigits: 2 }
    );
  }
};

window.populateCategoryEdit = function (data) {
  if (!data) return;

  const idField = document.getElementById("cat_edit_id");
  const nameField = document.getElementById("cat_edit_name");
  const descField = document.getElementById("cat_edit_description");

  if (idField) idField.value = data.id;
  if (nameField) nameField.value = data.name;
  if (descField) descField.value = data.description || "";
};

window.populatePromotionEdit = function (data, associatedProductIds) {
  if (!data) return;

  const idField = document.getElementById("edit_promo_id");
  const nameField = document.getElementById("edit_promo_name");
  const discField = document.getElementById("edit_promo_discount");
  const startField = document.getElementById("edit_promo_start");
  const endField = document.getElementById("edit_promo_end");

  if (idField) idField.value = data.id;
  if (nameField) nameField.value = data.name;
  if (discField) discField.value = data.discount_percentage;
  if (startField) startField.value = data.start_date.replace(" ", "T");
  if (endField) endField.value = data.end_date.replace(" ", "T");

  const checkboxes = document.querySelectorAll(".edit-product-checkbox");
  checkboxes.forEach((cb) => (cb.checked = false));

  if (associatedProductIds && Array.isArray(associatedProductIds)) {
    associatedProductIds.forEach((prodId) => {
      const cb = document.getElementById("prod_edit_" + prodId);
      if (cb) cb.checked = true;
    });
  }
};

function showErrorMessage(container, form, message) {
  if (!container || !form) return;

  const alertDiv = document.createElement("div");
  alertDiv.className =
    "alert alert-danger py-2 small shadow-sm border-0 d-flex align-items-center mb-3";
  alertDiv.innerHTML = `<i class="fa-solid fa-circle-exclamation me-2"></i> <div>${message}</div>`;

  const existingAlert = container.querySelector(".alert");
  if (existingAlert) existingAlert.remove();

  container.insertBefore(alertDiv, form);

  const inputs = form.querySelectorAll("input");
  inputs.forEach((input) => input.classList.add("is-invalid"));
}

function handleAuthFeedback() {
  const urlParams = new URLSearchParams(window.location.search);
  const errorType = urlParams.get("error");
  const successType = urlParams.get("success");
  const dropdownToggle = document.querySelector(".btn-header-login");

  if ((errorType || successType) && dropdownToggle && window.bootstrap) {
    const dropdown = new bootstrap.Dropdown(dropdownToggle);
    dropdown.show();
  }

  if (errorType) {
    const message = ERROR_MESSAGES[errorType] || ERROR_MESSAGES["default"];
    const loginContainer = document.getElementById("login-screen");
    const loginForm = loginContainer
      ? loginContainer.querySelector("form")
      : null;

    showErrorMessage(loginContainer, loginForm, message);
  }

  if (successType === "reset_email_sent") {
    const loginView = document.getElementById("login-screen");
    const successView = document.getElementById(
      "forgot-password-success-screen"
    );

    if (loginView && successView) {
      loginView.classList.add("d-none");
      successView.classList.remove("d-none");
    }
  }

  if (errorType || successType) {
    const newUrl =
      window.location.protocol +
      "//" +
      window.location.host +
      window.location.pathname;
    window.history.replaceState({ path: newUrl }, "", newUrl);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const dropdowns = document.querySelectorAll(".login-dropdown-menu");
  dropdowns.forEach((drop) => {
    drop.addEventListener("click", function (e) {
      e.stopPropagation();
    });
  });

  const loginFormElement = document.querySelector("#login-screen form");

  if (loginFormElement) {
    loginFormElement.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch("/login", {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          if (response.redirected) {
            const url = new URL(response.url);
            const error = url.searchParams.get("error");

            if (error) {
              const message =
                ERROR_MESSAGES[error] || ERROR_MESSAGES["default"];
              const loginContainer = document.getElementById("login-screen");
              showErrorMessage(loginContainer, this, message);
            } else {
              window.location.reload();
            }
          } else {
            window.location.reload();
          }
        })
        .catch((err) => {
          console.error(err);
          const loginContainer = document.getElementById("login-screen");
          showErrorMessage(loginContainer, this, ERROR_MESSAGES["default"]);
        });
    });
  }

  handleAuthFeedback();
});
