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

    <div id="tela-registro" class="d-none">
        <h6 class="login-title mb-3">Crie sua conta</h6>
        <form action="/register" method="POST">
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Nome Completo</label>
                <input type="text" class="form-control" required placeholder="Ex: João Inacio Gleison">
            </div>
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">E-mail</label>
                <input type="email" class="form-control" placeholder="ex: joao@email.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Senha</label>
                <input type="password" class="form-control" placeholder="*********" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Cadastrar</button>
        </form>

        <div class="dropdown-divider my-3"></div>

        <div class="text-center">
            <span class="small text-muted">Já tem conta?</span><br>
            <button type="button" class="btn btn-link btn-sm fw-bold text-decoration-none" onclick="alternarTelas(event)">
                Fazer Login
            </button>
        </div>
    </div>

</div>

<script>
    function alternarTelas(event) {
        // Impede que o clique no botão feche o dropdown do Bootstrap
        event.stopPropagation();
        event.preventDefault();

        const loginView = document.getElementById('tela-login');
        const registerView = document.getElementById('tela-registro');

        // Alterna as classes d-none (display: none) do Bootstrap
        if (loginView.classList.contains('d-none')) {
            // Mostra Login, Esconde Registro
            loginView.classList.remove('d-none');
            registerView.classList.add('d-none');
        } else {
            // Mostra Registro, Esconde Login
            loginView.classList.add('d-none');
            registerView.classList.remove('d-none');
        }
    }
    
    // Dica extra: Adicione isso para evitar que cliques dentro do formulário fechem o menu
    document.querySelector('.login-dropdown-menu').addEventListener('click', function (e) {
        e.stopPropagation();
    });
</script>