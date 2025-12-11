<?php
$pageTitle = 'Confirmar Pedido | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container my-5 flex-grow-1">
        <div class="row g-5 justify-content-center">

            <div class="col-md-8">
                <div class="mb-4">
                    <h1 class="h3 fw-bold text-brand mb-2">Revisão do Pedido</h1>
                    <p class="text-secondary">Confira os itens abaixo antes de enviar para análise.</p>
                </div>

                <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark">Itens do Carrinho</h5>
                    </div>
                    <ul class="list-group list-group-flush rounded-4 mb-4">
                        <li class="list-group-item bg-light fw-bold text-secondary small text-uppercase d-flex justify-content-between">
                            <span>Produto</span>
                            <span>Valores (Unit.)</span>
                        </li>

                        <?php foreach ($cartItems as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" class="rounded border me-3" style="width: 50px; height: 50px; object-fit: contain;">
                                    <div>
                                        <h6 class="my-0 fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></h6>
                                        <small class="text-muted">Qtd: <?= $item['quantity'] ?></small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="text-success fw-bold small">R$ <?= number_format($item['price'], 2, ',', '.') ?> (à vista)</div>
                                    <div class="text-secondary small">R$ <?= number_format($item['price_installments'], 2, ',', '.') ?> (prazo)</div>
                                </div>
                            </li>
                        <?php endforeach; ?>

                        <li class="list-group-item bg-light">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span class="text-secondary">Total à Vista:</span>
                                <span class="fw-bold text-success fs-5">R$ <?= number_format($totalCash, 2, ',', '.') ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <span class="text-muted small">Total a Prazo:</span>
                                <span class="fw-bold text-secondary">R$ <?= number_format($totalInstallments, 2, ',', '.') ?></span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-clipboard-check me-2 text-primary"></i>Finalizar Solicitação</h5>

                    <div class="alert alert-warning border-0 d-flex align-items-center gap-3 mb-4">
                        <i class="fa-solid fa-headset fa-2x"></i>
                        <div class="small">
                            <strong>Atenção:</strong> O pagamento não é realizado agora.
                            Ao confirmar, um de nossos consultores receberá seu pedido e entrará em contato para combinar entrega e pagamento.
                        </div>
                    </div>

                    <form action="/checkout/process" method="POST">
                        <div class="d-flex gap-3">
                            <a href="/carrinho" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">Voltar</a>
                            <button class="btn btn-buy w-100 rounded-pill fw-bold py-2" type="submit">
                                Confirmar e Enviar para Análise
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>
