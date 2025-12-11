<?php
$pageTitle = 'Gerenciar Promoções | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header_admin.php'; ?>

    <main class="container my-5 flex-grow-1">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="/admin/dashboard" class="btn btn-outline-secondary shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h3 mb-0 text-brand fw-bold"><i class="fa-solid fa-tags me-2 text-danger"></i>Promoções</h1>
                    <p class="text-secondary small mb-0">Gerencie campanhas e ofertas sazonais.</p>
                </div>
            </div>

            <button type="button" class="btn btn-buy fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newPromotionModal">
                <i class="fa-solid fa-plus me-2"></i>Nova Campanha
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="fa-solid fa-circle-check me-2"></i> Operação realizada com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small text-uppercase">Campanha</th>
                                <th class="py-3 text-secondary small text-uppercase">Desconto</th>
                                <th class="py-3 text-secondary small text-uppercase">Período</th>
                                <th class="py-3 text-secondary small text-uppercase text-center">Itens</th>
                                <th class="py-3 text-secondary small text-uppercase">Status</th>
                                <th class="pe-4 py-3 text-end text-secondary small text-uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($promotions)): ?>
                                <?php foreach ($promotions as $promo): ?>
                                    <?php
                                    $now = date('Y-m-d H:i:s');
                                    $isActiveDate = $now >= $promo['start_date'] && $now <= $promo['end_date'];
                                    $statusClass = $promo['active'] && $isActiveDate ? 'bg-success text-success' : 'bg-secondary text-secondary';
                                    $statusText = $promo['active'] ? ($isActiveDate ? 'Ativa' : 'Expirada/Agendada') : 'Inativa';
                                    ?>
                                    <tr class="<?= $promo['active'] ? '' : 'opacity-50' ?>">
                                        <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($promo['name']) ?></td>
                                        <td class="fw-bold text-danger"><?= number_format($promo['discount_percentage'], 0) ?>%</td>
                                        <td class="small text-secondary">
                                            <?= date('d/m/Y', strtotime($promo['start_date'])) ?> até
                                            <?= date('d/m/Y', strtotime($promo['end_date'])) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border"><?= $promo['product_count'] ?> produtos</span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $statusClass ?> bg-opacity-10 border border-opacity-25 rounded-pill px-2">
                                                <?= $statusText ?>
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end text-nowrap">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button class="btn btn-sm btn-outline-secondary border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPromotionModal"
                                                    onclick='populatePromotionEdit(<?= json_encode($promo) ?>, <?= json_encode(!empty($promo["product_ids"]) ? explode(",", $promo["product_ids"]) : []) ?>)'>
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>

                                                <form action="/admin/promotions/toggle" method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= $promo['id'] ?>">
                                                    <button type="submit" class="btn btn-sm border-0 <?= $promo['active'] ? 'text-warning' : 'text-success' ?>" title="Alterar Status">
                                                        <i class="fa-solid <?= $promo['active'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                                    </button>
                                                </form>

                                                <form action="/admin/promotions/delete" method="POST" class="d-inline" onsubmit="return confirm('Excluir esta promoção?');">
                                                    <input type="hidden" name="id" value="<?= $promo['id'] ?>">
                                                    <button type="submit" class="btn btn-sm border-0 text-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Nenhuma promoção cadastrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="newPromotionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-brand">Nova Campanha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <form action="/admin/promotions/create" method="POST">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Nome da Campanha</label>
                                <input type="text" name="name" class="form-control" required placeholder="Ex: Black Friday">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Desconto (%)</label>
                                <input type="number" step="0.1" name="discount" class="form-control" required placeholder="10">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Início</label>
                                <input type="datetime-local" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Fim</label>
                                <input type="datetime-local" name="end_date" class="form-control" required>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label small fw-bold text-secondary mb-2">Selecione os Produtos</label>
                                <div class="card p-2" style="max-height: 200px; overflow-y: auto;">
                                    <?php foreach ($products as $prod): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="products[]" value="<?= $prod->id ?>" id="prod_new_<?= $prod->id ?>">
                                            <label class="form-check-label small" for="prod_new_<?= $prod->id ?>">
                                                <?= htmlspecialchars($prod->name) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-buy w-100 fw-bold rounded-pill mt-4">Criar Campanha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPromotionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-brand">Editar Campanha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <form action="/admin/promotions/update" method="POST">
                        <input type="hidden" name="id" id="edit_promo_id">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Nome</label>
                                <input type="text" name="name" id="edit_promo_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Desconto (%)</label>
                                <input type="number" step="0.1" name="discount" id="edit_promo_discount" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Início</label>
                                <input type="datetime-local" name="start_date" id="edit_promo_start" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Fim</label>
                                <input type="datetime-local" name="end_date" id="edit_promo_end" class="form-control" required>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label small fw-bold text-secondary mb-2">Produtos da Campanha</label>
                                <div class="card p-2" style="max-height: 200px; overflow-y: auto;">
                                    <?php foreach ($products as $prod): ?>
                                        <div class="form-check">
                                            <input class="form-check-input edit-product-checkbox" type="checkbox" name="products[]" value="<?= $prod->id ?>" id="prod_edit_<?= $prod->id ?>">
                                            <label class="form-check-label small" for="prod_edit_<?= $prod->id ?>">
                                                <?= htmlspecialchars($prod->name) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-buy w-100 fw-bold rounded-pill mt-4">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>
