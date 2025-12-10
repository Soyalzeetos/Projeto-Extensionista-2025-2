<?php require __DIR__ . '/../partials/head.php'; ?>

<main class="container d-flex flex-column justify-content-center align-items-center py-5 flex-grow-1">

    <div class="card auth-card shadow-lg">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="fas fa-lock fa-3x text-accent mb-3"></i>
                <h2 class="fw-bold text-brand">Redefinir Senha</h2>
                <p class="text-muted">Informe sua nova senha abaixo.</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php
                    if ($_GET['error'] === 'password_mismatch') echo 'As senhas não coincidem.';
                    elseif ($_GET['error'] === 'token_invalid') echo 'Token inválido ou expirado.';
                    else echo 'Ocorreu um erro. Tente novamente.';
                    ?>
                </div>
            <?php endif; ?>

            <form action="/reset-password" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Nova Senha</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-key text-muted"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 6 caracteres" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-bold">Confirmar Nova Senha</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-check-double text-muted"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme a sua senha" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-buy btn-lg fw-bold">
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
