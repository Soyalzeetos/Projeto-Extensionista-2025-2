<div class="dropdown-menu dropdown-menu-end login-dropdown-menu">
    <h6 class="login-title">Acesse sua conta</h6>

    <form action="/login" method="POST">
        <div class="mb-3">
            <label for="emailLogin" class="form-label small fw-bold text-secondary">E-mail</label>
            <input type="email" class="form-control" id="emailLogin" name="email" placeholder="ex: joao@email.com" required>
        </div>

        <div class="mb-3">
            <label for="senhaLogin" class="form-label small fw-bold text-secondary">Senha</label>
            <input type="password" class="form-control" id="senhaLogin" name="password" placeholder="Digite sua senha" required>
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                <label class="form-check-label small" for="rememberMe">Lembrar</label>
            </div>
            <a href="/recuperar-senha" class="login-link small">Esqueceu a senha?</a>
        </div>

        <button type="submit" class="btn btn-login w-100 py-2 rounded-pill">Entrar</button>
    </form>

    <div class="dropdown-divider my-3"></div>

    <div class="text-center">
        <span class="small text-muted">Ainda não tem conta?</span><br>
        <a class="login-link fw-bold" href="/cadastro">Cadastre-se aqui</a>
    </div>
</div>
