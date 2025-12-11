<?php
$pageTitle = 'Gerenciar Produtos | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container my-5 flex-grow-1">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="/admin/dashboard" class="btn btn-outline-secondary shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Voltar ao Dashboard">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h3 mb-0 text-brand fw-bold"><i class="fa-solid fa-box-open me-2 text-primary"></i>Catálogo de Produtos</h1>
                    <p class="text-secondary small mb-0">Gerencie seu estoque, preços e imagens.</p>
                </div>
            </div>

            <button type="button" class="btn btn-buy fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newProductModal">
                <i class="fa-solid fa-plus me-2"></i>Novo Produto
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?php
                echo match ($_GET['success']) {
                    'created' => 'Produto cadastrado com sucesso!',
                    'updated' => 'Dados do produto atualizados!',
                    'deleted' => 'Produto removido do catálogo.',
                    default => 'Operação realizada com sucesso!'
                };
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert badge-notification alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?php
                echo match ($_GET['error']) {
                    'missing_fields' => 'Preencha todos os campos obrigatórios.',
                    'update_failed' => 'Não foi possível atualizar o produto.',
                    'delete_failed' => 'Erro ao excluir o produto.',
                    default => 'Ocorreu um erro inesperado.'
                };
                ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small text-uppercase" style="width: 40%;">Produto</th>
                                <th class="py-3 text-secondary small text-uppercase">À vista</th>
                                <th class="py-3 text-secondary small text-uppercase">Prazo</th>
                                <th class="pe-4 py-3 text-end text-secondary small text-uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $prod): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded border me-3 d-flex align-items-center justify-content-center bg-white" style="width: 50px; height: 50px; flex-shrink: 0;">
                                                    <img src="<?= htmlspecialchars($prod->imageUrl) ?>" alt="Img" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                                <div style="min-width: 0;">
                                                    <h6 class="mb-0 fw-bold text-dark text-truncate"><?= htmlspecialchars($prod->name) ?></h6>
                                                    <small class="text-muted text-truncate d-block"><?= htmlspecialchars($prod->description) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success"><?= $prod->getFormattedCashPrice() ?></td>
                                        <td class="text-secondary"><?= $prod->getFormattedInstallmentPrice() ?></td>
                                        <td class="pe-4 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-sm btn-outline-secondary border-0"
                                                    title="Editar"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editProductModal"
                                                    onclick='populateProductEdit(<?= json_encode([
                                                                                        "id" => $prod->id,
                                                                                        "name" => $prod->name,
                                                                                        "description" => $prod->description,
                                                                                        "price_cash" => $prod->priceCash,
                                                                                        "price_installments" => $prod->priceInstallments,
                                                                                        "category_id" => $prod->categoryId ?? 1
                                                                                    ]) ?>)'>
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>

                                                <form action="/admin/products/delete" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.');">
                                                    <input type="hidden" name="id" value="<?= $prod->id ?>">
                                                    <button type="submit" class="btn btn-sm border-0 text-danger" title="Excluir">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-box-open fa-2x mb-3 text-secondary opacity-50"></i><br>
                                        Nenhum produto cadastrado no momento.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="newProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-brand">Cadastrar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/admin/products/create" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Nome do Produto</label>
                                <input type="text" name="name" class="form-control" required placeholder="Ex: Parafusadeira Makita 12V">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Categoria</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Descrição</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Principais características..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Preço à Vista</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">R$</span>
                                    <input type="number" step="0.01" name="price_cash" class="form-control border-start-0 ps-1" required placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Preço a Prazo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">R$</span>
                                    <input type="number" step="0.01" name="price_installments" class="form-control border-start-0 ps-1" required placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Imagem</label>
                                <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/webp">
                                <div class="form-text small">Formatos aceitos: JPG, PNG, WEBP.</div>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-buy fw-bold py-2 rounded-pill">
                                Salvar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-brand">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/admin/products/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Nome do Produto</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Categoria</label>
                                <select name="category_id" id="edit_category_id" class="form-select" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Descrição</label>
                                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Preço à Vista</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">R$</span>
                                    <input type="number" step="0.01" name="price_cash" id="edit_price_cash" class="form-control border-start-0 ps-1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Preço a Prazo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">R$</span>
                                    <input type="number" step="0.01" name="price_installments" id="edit_price_installments" class="form-control border-start-0 ps-1" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Alterar Imagem</label>
                                <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/webp">
                                <div class="form-text small text-muted">Deixe vazio para manter a imagem atual.</div>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-buy fw-bold py-2 rounded-pill">
                                Atualizar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

</body>

</html>
