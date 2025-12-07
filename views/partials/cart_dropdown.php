 <div class="dropdown-menu dropdown-menu-end cart-dropdown-menu">
     <h6 class="title">Carrinho</h6>
     <div class="cart-items">
         <?php if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])): ?>
             <?php foreach ($_SESSION['cart'] as $item): ?>
                 <div class="cart-item d-flex align-items-center mb-3">
                     <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-image me-3" />
                     <div class="cart-item-details flex-grow-1">
                         <span class="cart-item-name d-block fw-bold"><?= htmlspecialchars($item['name']) ?></span>
                         <span class="cart-item-quantity text-secondary">Quantidade: <?= (int)$item['quantity'] ?></span>
                     </div>
                     <span class="cart-item-price fw-bold">R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></span>
                 </div>
             <?php endforeach; ?>
             <div class="dropdown-divider my-2"></div>
             <a href="/carrinho" class="btn btn-primary w-100 py-2">Ver Carrinho</a>
         <?php else: ?>
             <p class="text-center text-secondary mb-0">Seu carrinho está vazio.</p>
         <?php endif; ?>
     </div>
 </div>