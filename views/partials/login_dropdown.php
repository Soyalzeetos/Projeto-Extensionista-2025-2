<div class="dropdown-menu dropdown-menu-end login-dropdown-menu p-3" style="min-width: 300px;">

    <div id="tela-login">
        <h6 class="login-title mb-3">Acesse sua conta</h6>
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

            <button type="submit" class="btn btn-primary w-100 py-2">Entrar</button>
        </form>

        <div class="dropdown-divider my-3"></div>

        <div class="text-center">
            <span class="small text-muted">Ainda não tem conta?</span><br>
            <button type="button" class="btn btn-link btn-sm fw-bold text-decoration-none" onclick="alternarTelas(event)">
                Cadastre-se aqui
            </button>
        </div>
    </div>

    <?php require __DIR__ . '/register_dropdown.php' ?>

</div>

<script>
    function alternarTelas(event) {

        event.stopPropagation();
        event.preventDefault();

        const loginView = document.getElementById('tela-login');
        const registerView = document.getElementById('tela-registro');

        if (loginView.classList.contains('d-none')) {
            loginView.classList.remove('d-none');
            registerView.classList.add('d-none');
        } else {
            loginView.classList.add('d-none');
            registerView.classList.remove('d-none');
        }
    }

    document.querySelector('.login-dropdown-menu').addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>