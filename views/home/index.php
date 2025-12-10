<?php
$pageTitle = 'Center Ferramentas | Home';
require __DIR__ . '/../partials/head.php';
?>

<body class="bg-light d-flex flex-column min-vh-100">

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="flex-grow-1">
        <div class="container-fluid marquee text-nowrap py-2 overflow-hidden border-bottom border-dark border-2">
            <div class="marquee-track d-flex">
                <div class="marquee-group d-flex gap-5 pe-5" aria-hidden="true">
                    <?php for ($i = 0; $i < 8; $i++): ?>
                        <span class="fw-bold text-uppercase">🛠️ CENTER FERRAMENTAS - QUALIDADE, FORÇA E CONFIANÇA PRA SUA OBRA! 🔧 GRANDES OFERTAS TODOS OS DIAS! 💥</span>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <?php require __DIR__ . '/../partials/promotion_carousel.php'; ?>

        <section class="secao-vendas container mb-5 my-5 rounded-4 g-5">
            <h2 class="pt-3 mb-4 texto-secao">Nossos produtos</h2>
            <div class="row g-4 container-produtos">

                <?php foreach ($products as $product): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="d-flex justify-content-center align-items-center p-3" style="height: 250px;">
                                <a href="/produto?id=<?= $product->id ?>" class="d-flex justify-content-center align-items-center w-100 h-100 text-decoration-none">
                                    <img src="<?= htmlspecialchars($product->imageUrl) ?>" class="card-img-top mw-100 mh-100" style="object-fit: contain;" alt="<?= htmlspecialchars($product->name) ?>">
                                </a>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div>
                                    <h5 class="card-title fw-bold text-dark">
                                        <a href="/produto?id=<?= $product->id ?>" class="text-dark text-decoration-none">
                                            <?= htmlspecialchars($product->name) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text small text-secondary text-truncate"><?= htmlspecialchars($product->description) ?></p>
                                </div>

                                <div class="mt-auto w-100">
                                    <div class="mb-3">
                                        <p class="card-text fw-bold fs-5 primary-text-color mb-0"><?= $product->getFormattedCashPrice() ?></p>
                                        <small class="text-muted d-block" style="font-size: 0.8rem;">ou <?= $product->getFormattedInstallmentPrice() ?> a prazo</small>
                                    </div>

                                    <?php require __DIR__ . '/../partials/options_buy_cart.php' ?>

                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

    
</body>

</html>
