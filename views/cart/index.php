<?php
$pageTitle = 'Meu Carrinho | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container my-5 flex-grow-1">
        <div class="mb-4">
            <h1 class="h3 fw-bold text-brand"><i class="fa-solid fa-cart-shopping me-2"></i>Seu Carrinho</h1>
        </div>

        <?php if (empty($cartItems)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-basket-shopping fa-4x text-muted opacity-25 mb-3"></i>
                <h3 class="fw-bold text-secondary">Carrinho vazio</h3>
                <a href="/" class="btn btn-buy rounded-pill mt-3 px-4">Voltar a Comprar</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Produto</th>
                                        <th class="text-center">Qtd</th>
                                        <th class="text-end pe-4">Preço Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems as $item): ?>
                                        <tr id="cart-row-<?= $item['id'] ?>">
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= htmlspecialchars($item['image']) ?>" class="rounded border me-3" style="width: 60px; height: 60px; object-fit: contain;">
                                                    <div>
                                                        <a href="/produto?id=<?= $item['id'] ?>" class="fw-bold text-dark text-decoration-none">
                                                            <?= htmlspecialchars($item['name']) ?>
                                                        </a>
                                                        <div class="d-flex flex-column mt-1">
                                                            <small class="text-success fw-bold" style="font-size: 0.8rem;">
                                                                R$ <?= number_format($item['price'], 2, ',', '.') ?> (à vista)
                                                            </small>
                                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                                ou R$ <?= number_format($item['price_installments'], 2, ',', '.') ?> (a prazo)
                                                            </small>
                                                        </div>
                                                        <button onclick="removeCartItem(<?= $item['id'] ?>)" class="btn btn-link btn-sm text-danger p-0 text-decoration-none small mt-1">
                                                            <i class="fa-solid fa-trash me-1"></i> Remover
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center" style="width: 140px;">
                                                <div class="input-group input-group-sm">
                                                    <button class="btn btn-outline-secondary" onclick="changeQty(<?= $item['id'] ?>, <?= $item['quantity'] ?>, -1)">-</button>
                                                    <input type="text" id="qty-input-<?= $item['id'] ?>" class="form-control text-center" value="<?= $item['quantity'] ?>" readonly>
                                                    <button class="btn btn-outline-secondary" onclick="changeQty(<?= $item['id'] ?>, <?= $item['quantity'] ?>, 1)">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="fw-bold text-success" id="total-cash-<?= $item['id'] ?>">
                                                    R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                                                </div>
                                                <div class="text-muted small" id="total-inst-<?= $item['id'] ?>">
                                                    ou R$ <?= number_format($item['price_installments'] * $item['quantity'], 2, ',', '.') ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="/" class="btn btn-link text-decoration-none text-secondary fw-bold">
                            <i class="fa-solid fa-arrow-left me-2"></i> Continuar Comprando
                        </a>
                    </div>

                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-3">Resumo do Pedido</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Itens</span>
                            <span class="fw-bold" id="summary-count"><?= array_sum(array_column($cartItems, 'quantity')) ?></span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-1 align-items-end">
                            <span>Total à Vista:</span>
                            <span class="fs-5 fw-bold text-success" id="summary-total-cash">R$ <?= number_format($totalCash, 2, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 align-items-end">
                            <span class="text-muted small">Total a Prazo:</span>
                            <span class="fw-bold text-secondary" id="summary-total-inst">R$ <?= number_format($totalInstallments, 2, ',', '.') ?></span>
                        </div>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="/checkout" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm">
                                <i class="fa-solid fa-lock me-2"></i> Ir para Pagamento
                            </a>
                        <?php else: ?>
                            <div class="alert alert-warning small border-0 text-center mb-3 p-2">
                                Entre para finalizar a compra
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-buy w-100 py-3 rounded-pill fw-bold shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                    Entrar / Cadastrar
                                </button>
                                <?php require __DIR__ . '/../partials/login_dropdown.php'; ?>
                            </div>
                        <?php endif; ?>

                        <div class="text-center mt-3">
                            <small class="text-muted"><i class="fa-solid fa-shield-halved me-1"></i> Ambiente Seguro</small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>
