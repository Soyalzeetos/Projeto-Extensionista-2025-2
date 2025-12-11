<div id="register-screen" class="d-none">
    <h6 class="login-title mb-3">Crie sua conta</h6>
    <form action="/register" method="POST" novalidate>

        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Nome Completo</label>
            <input type="text" class="form-control" name="name" required placeholder="Ex: João Silva">
        </div>

        <div class="mb-3">
            <label for="emailRegister" class="form-label small fw-bold text-secondary">E-mail</label>
            <input type="email" class="form-control" id="emailRegister" name="email"
                placeholder="ex: joao@email.com" required oninput="validateEmail(this)">
            <div class="invalid-feedback small">Insira um e-mail válido.</div>
        </div>

        <div class="mb-3">
            <label for="telefoneRegister" class="form-label small fw-bold text-secondary">Telefone</label>
            <input type="tel" class="form-control" id="telefoneRegister" name="phone"
                placeholder="(99) 99999-9999" required oninput="phoneMask(this)">
        </div>

        <div class="mb-3">
            <label for="senhaRegister" class="form-label small fw-bold text-secondary">Senha</label>
            <div class="input-group">
                <input type="password" class="form-control" id="senhaRegister" name="password"
                    placeholder="*********" required oninput="validatePasswordConfirmation()">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('senhaRegister', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label for="senhaRegisterConf" class="form-label small fw-bold text-secondary">Confirmar Senha</label>
            <div class="input-group">
                <input type="password" class="form-control" id="senhaRegisterConf" name="password_confirmation"
                    placeholder="Confirme a sua senha" required oninput="validatePasswordConfirmation()">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('senhaRegisterConf', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
                <div class="invalid-feedback">As senhas não coincidem.</div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Cadastrar</button>
    </form>

    <div class="dropdown-divider my-3"></div>

    <div class="text-center">
        <span class="small text-muted">Já tem conta?</span><br>
        <button type="button" class="btn btn-link btn-sm fw-bold text-decoration-none" onclick="switchScreens(event)">
            Fazer Login
        </button>
    </div>
</div>
