<div class="d-flex gap-2 align-items-center">
    <a href="javascript:void(0)"
        class="btn btn-buy rounded-pill flex-grow-1 fw-bold"
        onclick='WhatsappMessage(<?= json_encode($product->name) ?>)'>
        Comprar
    </a>

    <button type="button"
        class="btn-add-cart shadow-sm"
        onclick="addToCart(<?= $product->id ?>)"
        title="Adicionar ao Carrinho">
        <i class="fa-solid fa-cart-plus"></i>
    </button>
</div>

<script>
    if (!window.WhatsappMessage) {
        window.WhatsappMessage = function(nomeProduto) {
            let telefone = "553493415258";
            let mensagem = encodeURIComponent("Olá, tenho interesse em: " + nomeProduto);
            let url = `https://wa.me/${telefone}?text=${mensagem}`;
            // Abre em nova aba
            window.open(url, '_blank');
        }
    }
</script>