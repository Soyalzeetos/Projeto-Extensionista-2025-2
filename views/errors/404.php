<?php
$pageTitle = 'Página não encontrada | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container d-flex flex-column justify-content-center align-items-center flex-grow-1 my-5">
        <div class="text-center p-5 rounded-4 shadow-sm bg-white" style="max-width: 600px;">
            <div class="mb-4">
                <i class="fa-solid fa-map-location-dot fa-6x text-secondary opacity-25"></i>
            </div>

            <h1 class="display-1 fw-bold text-brand">404</h1>
            <h2 class="h4 mb-3 fw-bold text-dark">Ops! Não encontramos essa página.</h2>

            <p class="text-secondary mb-4">
                A página que você está procurando pode ter sido removida, renomeada ou não existe.
                Verifique o endereço digitado ou volte para o início.
            </p>

            <div class="d-flex gap-2 justify-content-center">
                <a href="/" class="btn btn-buy rounded-pill px-4 py-2 fw-bold">
                    <i class="fa-solid fa-house me-2"></i> Ir para o Início
                </a>
                <a href="#" onclick="history.back()" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold">
                    Voltar
                </a>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>
