<?php
$pageTitle = 'Meus Pedidos | Center Ferramentas';
require __DIR__ . '/../partials/head.php';

function getClientStatusBadge(string $status): string
{
    return match ($status) {
        'pending' => '<span class="badge bg-warning text-dark"><i class="fa-regular fa-clock me-1"></i>Em Análise</span>',
        'awaiting_payment' => '<span class="badge bg-info text-dark">Aguardando Pagamento</span>',
        'paid' => '<span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Pago</span>',
        'processing' => '<span class="badge bg-primary">Separando Pedido</span>',
        'shipped' => '<span class="badge bg-primary"><i class="fa-solid fa-truck-fast me-1"></i>Enviado</span>',
        'delivered' => '<span class="badge bg-success">Entregue</span>',
        'cancelled' => '<span class="badge bg-danger">Cancelado</span>',
        default => '<span class="badge bg-secondary">Desconhecido</span>',
    };
}
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container my-5 flex-grow-1">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="/" class="btn btn-outline-secondary shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="h3 mb-0 text-brand fw-bold">Minhas Compras</h1>
        </div>

        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-box-open fa-4x text-muted opacity-25 mb-3"></i>
                <h3 class="fw-bold text-secondary">Nenhum pedido encontrado</h3>
                <p class="text-muted">Você ainda não realizou nenhuma compra conosco.</p>
                <a href="/" class="btn btn-buy rounded-pill mt-3 px-4 fw-bold">Começar a Comprar</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($orders as $order): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                            <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-bold text-brand mb-1">Pedido #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></h5>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($order['created_at'])) ?> às <?= date('H:i', strtotime($order['created_at'])) ?>
                                    </small>
                                </div>
                                <?= getClientStatusBadge($order['status']) ?>
                            </div>

                            <div class="card-body px-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary small">Total (À vista):</span>
                                    <span class="fw-bold text-success">R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-secondary small">Total (A prazo):</span>
                                    <span class="fw-bold text-muted">R$ <?= number_format($order['total_amount_installments'], 2, ',', '.') ?></span>
                                </div>
                                <hr class="my-3 opacity-10">
                                <div class="d-flex align-items-center gap-2">
                                    <?php
                                    $count = 0;
                                    foreach ($order['items'] as $item):
                                        if ($count >= 3) break;
                                    ?>
                                        <img src="<?= htmlspecialchars($item['image']) ?>" class="rounded border bg-white" style="width: 40px; height: 40px; object-fit: contain;" title="<?= htmlspecialchars($item['product_name']) ?>">
                                    <?php $count++;
                                    endforeach; ?>

                                    <?php if (count($order['items']) > 3): ?>
                                        <span class="badge bg-light text-secondary border rounded-circle p-2">+<?= count($order['items']) - 3 ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-top-0 pb-4 px-4">
                                <button class="btn btn-outline-primary w-100 rounded-pill fw-bold"
                                    data-bs-toggle="modal"
                                    data-bs-target="#clientOrderModal"
                                    onclick='openClientOrderDetails(<?= json_encode($order) ?>)'>
                                    Ver Detalhes
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <div class="modal fade" id="clientOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold text-brand" id="modalTitle">Pedido #0000</h5>
                        <p class="small text-muted mb-0" id="modalDate">Data: --/--/----</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border d-flex align-items-center gap-3 mb-4">
                        <i class="fa-solid fa-circle-info text-accent fa-lg"></i>
                        <div class="small text-secondary">
                            Acompanhe o status do seu pedido. Caso precise de ajuda, entre em contato pelo WhatsApp informando o número do pedido.
                        </div>
                    </div>

                    <h6 class="fw-bold text-secondary text-uppercase small mb-3">Itens Comprados</h6>
                    <div class="table-responsive mb-3">
                        <table class="table align-middle">
                            <tbody id="modalItemsBody">
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total (À Vista)</td>
                                    <td class="text-end fw-bold text-success" id="modalTotalCash">R$ 0,00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end small text-muted">Total (A Prazo)</td>
                                    <td class="text-end fw-bold text-secondary" id="modalTotalInst">R$ 0,00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
                    <a href="https://wa.me/5534991975188" target="_blank" class="btn btn-success rounded-pill px-4 fw-bold">
                        <i class="fa-brands fa-whatsapp me-2"></i> Ajuda com Pedido
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>
