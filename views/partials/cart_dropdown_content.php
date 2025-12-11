<?php
$cartItems = $cartItems ?? [];
$cartTotal = 0;
foreach ($cartItems as $item) $cartTotal += $item['price'] * $item['quantity'];
?>

<?php if (!empty($cartItems)): ?>
    <div class="cart-scrollable custom-scrollbar" style="max-height: 300px; overflow-y: auto;">
        <?php foreach ($cartItems as $item): ?>
            <div class="d-flex align-items-center mb-3 border-bottom pb-2">
                <img src="<?= htmlspecialchars($item['image']) ?>" class="rounded border me-2" style="width: 50px; height: 50px; object-fit: contain;">

                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0 small fw-bold text-truncate text-dark" style="max-width: 130px;"><?= htmlspecialchars($item['name']) ?></h6>
                        <button onclick="removeCartItem(<?= $item['id'] ?>)" class="btn btn-link text-danger p-0 ms-1" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="input-group input-group-sm" style="width: 80px;">
                            <button class="btn btn-outline-secondary p-0 px-1" type="button" onclick="changeQty(<?= $item['id'] ?>, <?= $item['quantity'] ?>, -1)">-</button>
                            <input type="text" class="form-control text-center p-0" value="<?= $item['quantity'] ?>" readonly>
                            <button class="btn btn-outline-secondary p-0 px-1" type="button" onclick="changeQty(<?= $item['id'] ?>, <?= $item['quantity'] ?>, 1)">+</button>
                        </div>

                        <div class="text-end">
                            <span class="d-block small text-muted" style="font-size: 0.7rem;">
                                <?= $item['quantity'] ?>x R$ <?= number_format($item['price'], 2, ',', '.') ?>
                            </span>
                            <span class="fw-bold text-primary small">
                                R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?> <span class="text-secondary" style="font-size: 0.8em;">(à vista)</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="p-2 bg-light rounded mt-2">
        <div class="d-flex justify-content-between fw-bold text-dark mb-2">
            <span>Total:</span>
            <span>R$ <?= number_format($cartTotal, 2, ',', '.') ?></span>
        </div>
        <a href="/carrinho" class="btn btn-primary w-100 btn-sm  fw-bold">Checkout</a>
    </div>

<?php else: ?>
    <div class="text-center py-4 text-secondary">
        <i class="fa-solid fa-basket-shopping fa-2x mb-2 opacity-50"></i>
        <p class="small mb-0">Seu carrinho está vazio.</p>
    </div>
<?php endif; ?>
