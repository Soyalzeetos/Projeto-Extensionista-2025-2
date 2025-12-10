<div id="tela-esqueci-senha" class="d-none">
    <h6 class="login-title mb-3 ">Recuperar Senha</h6>
    <p class="small text-secondary text-center mb-4">
        Digite seu e-mail abaixo, que caso tenha cadastro, enviaremos as instruções para redefinir sua senha.
    </p>

    <form action="/forgot-password" method="POST" novalidate>
        <div class="mb-3">
            <label for="emailForgot" class="form-label small fw-bold text-secondary">E-mail</label>
            <input type="email" class="form-control" id="emailForgot" name="email"
                placeholder="ex: joao@email.com" required oninput="validarEmail(this)">
            <div class="invalid-feedback small">Digite um e-mail válido.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Enviar</button>
    </form>

    <div class="dropdown-divider my-3"></div>

    <div class="text-center">
        <button type="button" class="btn btn-link btn-sm fw-bold text-decoration-none" onclick="voltarParaLogin(event)">
            <i class="fa-solid fa-arrow-left me-1"></i> Voltar para Login
        </button>
    </div>
</div>
