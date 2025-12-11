<div class="d-flex gap-2 align-items-center">
    <a href="/produto?id=<?= $product->id ?>" class="btn btn-buy rounded-pill flex-grow-1 fw-bold" onclick='WhatsappMessage(<?= json_encode($product->description) ?>)'>
        Comprar
    </a>

    <button type="button"
        class="btn-add-cart shadow-sm"
        onclick="addToCart(<?= $product->id ?>)"
        title="Adicionar ao Carrinho">
        <i class="fa-solid fa-cart-plus"></i>
    </button>
    <script>
    function WhatsappMessage() {
        let telefone = "553493415258";
        let produto = <?= json_encode($product->name) ?> ? > ;
        let mensagem = encodeURIComponent("Olá, tenho interesse em: " + name);
        let url = `https://wa.me/${telefone}?text=${mensagem}`;
        window.open(url, '_blank');
    }
</script>
</div>
