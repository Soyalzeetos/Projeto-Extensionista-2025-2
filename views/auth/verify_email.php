<?php
$pageTitle = 'Confirmação de E-mail | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container d-flex flex-column justify-content-center align-items-center flex-grow-1 py-5">

        <div class="card auth-card shadow-lg border-0 text-center p-4">
            <div class="card-body">
                <?php if (!empty($success)): ?>
                    <div class="mb-4">
                        <i class="fa-solid fa-circle-check fa-5x text-success"></i>
                    </div>

                    <h2 class="fw-bold text-brand mb-3">E-mail Confirmado!</h2>

                    <p class="text-secondary mb-4">
                        Sua conta foi ativada com sucesso. Agora você pode aproveitar todas as ofertas da Center Ferramentas.
                    </p>

                    <a href="/" class="btn btn-buy btn-lg w-100 rounded-pill fw-bold shadow-sm">
                        Ir para a Loja
                    </a>

                <?php else: ?>
                    <div class="mb-4">
                        <i class="fa-solid fa-circle-exclamation fa-5x text-danger"></i>
                    </div>

                    <h2 class="fw-bold text-secondary mb-3">Link Inválido</h2>

                    <p class="text-muted mb-4">
                        Não foi possível confirmar seu e-mail. O link pode ter expirado ou já foi utilizado.
                    </p>

                    <a href="/" class="btn btn-outline-dark w-100 rounded-pill fw-bold">
                        Voltar ao Início
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>
