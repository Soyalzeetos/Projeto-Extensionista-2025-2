<div class="d-flex gap-2 align-items-center">
    <a href="/produto?id=<?= $product->id ?>" class="btn btn-buy rounded-pill flex-grow-1 fw-bold">
        Comprar
    </a>

    <a href="/carrinho/adicionar?id=<?= $product->id ?>" class="btn-add-cart shadow-sm" title="Adicionar ao Carrinho">
        <i class="fa-solid fa-cart-plus"></i>
    </a>
</div>