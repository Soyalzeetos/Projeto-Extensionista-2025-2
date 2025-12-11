<?php
$pageTitle = 'Erro Interno | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container d-flex flex-column justify-content-center align-items-center flex-grow-1 my-5">
        <div class="text-center p-5 rounded-4 shadow-sm bg-white" style="max-width: 600px;">
            <div class="mb-4">
                <i class="fa-solid fa-screwdriver-wrench fa-6x text-accent opacity-50"></i>
            </div>

            <h1 class="display-4 fw-bold text-brand">Erro 500</h1>
            <h2 class="h4 mb-3 fw-bold text-dark">Tivemos um problema técnico.</h2>

            <p class="text-secondary mb-4">
                Nossa equipe de obras já foi notificada e está trabalhando no conserto.
                Por favor, tente recarregar a página em alguns instantes.
            </p>

            <a href="/" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                <i class="fa-solid fa-rotate-right me-2"></i> Recarregar Loja
            </a>
        </div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>
