const ERROR_MESSAGES = {
  invalid_credentials: "E-mail ou senha incorretos.",
  missing_fields: "Por favor, preencha todos os campos.",
  default: "Ocorreu um erro inesperado. Tente novamente.",
};

function toggleSearch() {
  const overlay = document.getElementById("mobile-search-overlay");
  const inputMobile = document.getElementById("campo-busca-mobile");

  if (overlay) {
    if (overlay.classList.contains("d-none")) {
      overlay.classList.remove("d-none");
      if (inputMobile) setTimeout(() => inputMobile.focus(), 100);
    } else {
      overlay.classList.add("d-none");
    }
  }
}

function alternarTelas(event) {
  if (event) {
    event.stopPropagation();
    event.preventDefault();
  }

  const loginView = document.getElementById("tela-login");
  const registerView = document.getElementById("tela-registro");

  if (loginView && registerView) {
    if (loginView.classList.contains("d-none")) {
      loginView.classList.remove("d-none");
      registerView.classList.add("d-none");
    } else {
      loginView.classList.add("d-none");
      registerView.classList.remove("d-none");
    }
  }
}

function alternarParaEsqueciSenha(event) {
  if (event) {
    event.stopPropagation();
    event.preventDefault();
  }

  const loginView = document.getElementById("tela-login");
  const forgotView = document.getElementById("tela-esqueci-senha");

  if (loginView && forgotView) {
    loginView.classList.add("d-none");
    forgotView.classList.remove("d-none");
  }
}

function voltarParaLogin(event) {
  if (event) {
    event.stopPropagation();
    event.preventDefault();
  }

  const views = [
    document.getElementById("tela-registro"),
    document.getElementById("tela-esqueci-senha"),
    document.getElementById("tela-esqueci-senha-sucesso"),
  ];
  const loginView = document.getElementById("tela-login");

  views.forEach((view) => {
    if (view) view.classList.add("d-none");
  });

  if (loginView) loginView.classList.remove("d-none");
}

function alternarSenha(inputId, btn) {
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
}

function validarEmail(input) {
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
}

function validarConfirmacaoSenha() {
  const senha = document.getElementById("senhaRegister");
  const confirmacao = document.getElementById("senhaRegisterConf");

  if (senha && confirmacao && confirmacao.value.length > 0) {
    if (senha.value === confirmacao.value) {
      confirmacao.classList.remove("is-invalid");
      confirmacao.classList.add("is-valid");
    } else {
      confirmacao.classList.remove("is-valid");
      confirmacao.classList.add("is-invalid");
    }
  } else if (confirmacao) {
    confirmacao.classList.remove("is-valid", "is-invalid");
  }
}

function mascaraTelefone(input) {
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
}

function exibirMensagemErro(container, form, message) {
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
    const loginContainer = document.getElementById("tela-login");
    const loginForm = loginContainer
      ? loginContainer.querySelector("form")
      : null;

    exibirMensagemErro(loginContainer, loginForm, message);
  }

  if (successType === "reset_email_sent") {
    const loginView = document.getElementById("tela-login");
    const successView = document.getElementById("tela-esqueci-senha-sucesso");

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

  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
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
              const loginContainer = document.getElementById("tela-login");
              exibirMensagemErro(loginContainer, this, message);
            } else {
              window.location.reload();
            }
          } else {
            window.location.reload();
          }
        })
        .catch((err) => {
          console.error(err);
          const loginContainer = document.getElementById("tela-login");
          exibirMensagemErro(loginContainer, this, ERROR_MESSAGES["default"]);
        });
    });
  }

  handleAuthFeedback();
});
