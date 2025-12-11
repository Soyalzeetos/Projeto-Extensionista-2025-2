<div class="dropdown-menu dropdown-menu-end cart-dropdown-menu shadow-lg border-0  p-0 overflow-hidden" style="min-width: 320px;">
    <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
        <h6 class="mb-0 fw-bold text-brand">Meu Carrinho</h6>
        <span class="badge bg-primary " id="cart-count-badge">
            <?= isset($cartQty) ? $cartQty : 0 ?>
        </span>
    </div>

    <div class="cart-items p-3">
        <?php require __DIR__ . '/cart_dropdown_content.php'; ?>
    </div>
</div>
