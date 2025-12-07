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

        <section class="container my-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 caixa-promocao p-3 p-md-4 rounded-4 shadow-lg">
                    <?php if (!empty($featured)): ?>
                        <div id="carouselPromocoes" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">

                            <div class="carousel-indicators mb-n4">
                                <?php foreach ($featured as $idx => $product): ?>
                                    <button type="button" data-bs-target="#carouselPromocoes" data-bs-slide-to="<?= $idx ?>"
                                        class="<?= $idx === 0 ? 'active' : '' ?>"
                                        aria-current="<?= $idx === 0 ? 'true' : 'false' ?>"></button>
                                <?php endforeach; ?>
                            </div>

                            <div class="carousel-inner">
                                <?php foreach ($featured as $idx => $product): ?>
                                    <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                                        <div class="card border-0 rounded-4 overflow-hidden h-100 position-relative" style="min-height: 400px">
                                            <div class="row g-0 h-100">
                                                <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4 card-img-container">
                                                    <a href="/produto?id=<?= $product->id ?>" class="d-flex justify-content-center w-100 h-100 align-items-center text-decoration-none">
                                                        <img src="<?= htmlspecialchars($product->imageUrl) ?>" class="img-fluid card-product-img" style="max-height: 250px; object-fit: contain" alt="<?= htmlspecialchars($product->name) ?>" />
                                                    </a>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-center">
                                                    <div class="card-body p-4 p-lg-5 text-center text-md-start">
                                                        <?php if ($product->getDiscountLabel()): ?>
                                                            <div class="mb-2">
                                                                <span class="badge bg-danger">OFERTA ESPECIAL <?= $product->getDiscountLabel() ?></span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <h3 class="card-title fw-bold text-primary mb-3"><?= htmlspecialchars($product->name) ?></h3>
                                                        <p class="card-text text-secondary fs-5 mb-4"><?= htmlspecialchars($product->description) ?></p>

                                                        <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                                            <span class="badge bg-warning text-dark fs-5 px-3 py-2 rounded-pill shadow-sm">
                                                                <?= $product->getFormattedCashPrice() ?> <small class="fw-normal">à vista</small>
                                                            </span>
                                                            <a href="/produto?id=<?= $product->id ?>" class="btn btn-primary rounded-pill px-4 fw-bold stretched-link">Comprar Agora</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button class="carousel-control-prev carousel-dark" type="button" data-bs-target="#carouselPromocoes" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next carousel-dark" type="button" data-bs-target="#carouselPromocoes" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Próximo</span>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-white py-5">
                            <h3>Nenhuma promoção ativa no momento.</h3>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="secao-vendas container mb-5 my-5 rounded-4 g-5">
            <h2 class="pt-3 mb-4 texto-secao">Nossos produtos</h2>
            <div class="container-fluid row g-4 container-produtos">

                <?php foreach ($products as $product): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="d-flex justify-content-center align-items-center p-3" style="height: 250px;">
                                <a href="/produto?id=<?= $product->id ?>" class="d-flex justify-content-center align-items-center w-100 h-100 text-decoration-none">
                                    <img src="<?= htmlspecialchars($product->imageUrl) ?>" class="card-img-top mw-100 mh-100" style="object-fit: contain;" alt="<?= htmlspecialchars($product->name) ?>">
                                </a>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-dark">
                                    <a href="/produto?id=<?= $product->id ?>" class="text-dark text-decoration-none">
                                        <?= htmlspecialchars($product->name) ?>
                                    </a>
                                </h5>
                                <p class="card-text small text-secondary text-truncate"><?= htmlspecialchars($product->description) ?></p>
                                <div class="mt-auto">
                                    <p class="card-text fw-bold fs-5 text-primary mb-1"><?= $product->getFormattedCashPrice() ?></p>
                                    <small class="text-muted d-block mb-2">ou <?= $product->getFormattedInstallmentPrice() ?> a prazo</small>

                                    <a href="/produto?id=<?= $product->id ?>" class="btn btn-primary w-100 rounded-pill">Comprar</a>
                                </div>
                                <a href="/produto?id=<?= $product->id ?>" class="stretched-link">Adicionar ao carrinho</a>

                            </div>
                            
                            
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
