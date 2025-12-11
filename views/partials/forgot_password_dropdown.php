<div id="forgot-password-screen" class="d-none">
    <h6 class="login-title mb-3 ">Recuperar Senha</h6>
    <p class="small text-secondary text-center mb-4">
        Digite seu e-mail abaixo, que caso tenha cadastro, enviaremos as instruções para redefinir sua senha.
    </p>

    <form action="/forgot-password" method="POST" novalidate>
        <div class="mb-3">
            <label for="emailForgot" class="form-label small fw-bold text-secondary">E-mail</label>
            <input type="email" class="form-control" id="emailForgot" name="email"
                placeholder="ex: joao@email.com" required oninput="validateEmail(this)">
            <div class="invalid-feedback small">Digite um e-mail válido.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Enviar</button>
    </form>

    <div class="dropdown-divider my-3"></div>

    <div class="text-center">
        <button type="button" class="btn btn-link btn-sm fw-bold text-decoration-none" onclick="backToLogin(event)">
            <i class="fa-solid fa-arrow-left me-1"></i> Voltar para Login
        </button>
    </div>
</div>

<div id="forgot-password-success-screen" class="d-none text-center py-4">
    <div class="mb-3">
        <i class="fa-regular fa-envelope-open fa-3x text-success"></i>
    </div>
    <h6 class="login-title mb-2">Verifique seu E-mail</h6>
    <p class="small text-secondary mb-4 px-2">
        Se o e-mail informado estiver cadastrado, você receberá um link para redefinir sua senha em instantes.
    </p>

    <button type="button" class="btn btn-outline-primary btn-sm fw-bold rounded-pill px-4" onclick="backToLogin(event)">
        Voltar para o Login
    </button>
</div>
