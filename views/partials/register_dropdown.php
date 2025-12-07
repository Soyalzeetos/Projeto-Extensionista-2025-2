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