<?php require __DIR__ . '/../partials/head.php'; ?>

<body class="bg-light d-flex flex-column min-vh-100">

    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main class="container my-5 flex-grow-1">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 bg-white d-flex align-items-center justify-content-center p-5">
                    <img src="<?= htmlspecialchars($product->imageUrl) ?>"
                        class="img-fluid"
                        style="max-height: 400px; object-fit: contain;"
                        alt="<?= htmlspecialchars($product->name) ?>">
                </div>

                <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center bg-light">

                    <h1 class="display-6 fw-bold text-primary mb-3"><?= htmlspecialchars($product->name) ?></h1>

                    <?php if ($product->getDiscountLabel()): ?>
                        <div class="mb-2">
                            <span class="badge bg-danger fs-6">OFERTA <?= $product->getDiscountLabel() ?> OFF</span>
                        </div>
                    <?php endif; ?>

                    <hr class="my-4">

                    <div class="d-flex align-items-end mb-4 flex-column align-items-start">
                        <div class="mt-1 d-flex align-items-end flex-column">
                            <span class="text-success small fw-bold ms-2 ">À vista no Pix</span>
                            <span class="fs-1 fw-bold primary-text-color"><?= $product->getFormattedCashPrice() ?></span>
                        </div>
                        <div>
                            <span class="text-muted small">Ou a prazo por apenas: </span>
                            <span class="d-inline-block text-muted fs-5">
                                <?= $product->getFormattedInstallmentPrice() ?>
                            </span>
                        </div>
                        <div class="mt-auto w-100">

                            <?php require __DIR__ . '/../partials/options_buy_cart.php' ?>
                        </div>

                        <?php if ($product->discountPercentage > 0): ?>
                            <small class="text-danger mt-1">
                                *Preços com desconto promocional aplicado em ambas as modalidades.
                            </small>
                        <?php endif; ?>

                    </div>
                    <hr class="my-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-2">Detalhes do Produto</h6>
                    <p class="lead text-secondary mb-4"><?= htmlspecialchars($product->description) ?></p>

                </div>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<style>
    body::-webkit-scrollbar {
    display: none;
}
</style>
</html>
