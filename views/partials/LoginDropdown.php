<div class="dropdown-menu dropdown-menu-end shadow p-3" style="min-width: 300px; z-index: 1050;">
    <form action="/login" method="POST">
        <div class="mb-3">
            <label for="emailLogin" class="form-label">Seu endereço de e-mail</label>
            <input type="email" class="form-control" id="emailLogin" name="email" placeholder="tangamandapio@email.com" required>
        </div>
        <div class="mb-3">
            <label for="senhaLogin" class="form-label">Senha</label>
            <input type="password" class="form-control" id="senhaLogin" name="password" placeholder="aquelasuasenhasuperdificil" required>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">
                    Lembrar de mim
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="/cadastro">Novo por aqui? Cadastre-se</a>
    <a class="dropdown-item" href="/recuperar-senha">Esqueceu a senha?</a>
</div>
