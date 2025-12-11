<?php
$pageTitle = 'Gerenciar Produtos | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header_admin.php'; ?>

    <main class="container my-5 flex-grow-1">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="/admin/dashboard" class="btn btn-outline-secondary shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Voltar ao Dashboard">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h3 mb-0 text-brand fw-bold"><i class="fa-solid fa-box-open me-2 text-primary"></i>Catálogo de Produtos</h1>
                    <p class="text-secondary small mb-0">Gerencie estoque, preços e visibilidade.</p>
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
                    'updated' => 'Produto atualizado!',
                    'status_changed' => 'Visibilidade alterada com sucesso!',
                    'deleted' => 'Produto removido permanentemente.',
                    default => 'Operação realizada com sucesso!'
                };
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small text-uppercase" style="width: 35%;">Produto</th>
                                <th class="py-3 text-secondary small text-uppercase" style="width: 15%;">Preço (À vista)</th>
                                <th class="py-3 text-secondary small text-uppercase text-center" style="width: 15%;">Estoque</th>
                                <th class="py-3 text-secondary small text-uppercase text-center" style="width: 10%;">Status</th>
                                <th class="pe-4 py-3 text-end text-secondary small text-uppercase" style="width: 25%;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $prod): ?>
                                    <tr class="<?= $prod->active ? '' : 'opacity-50 bg-light' ?>">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded border me-3 d-flex align-items-center justify-content-center bg-white" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    <img src="<?= htmlspecialchars($prod->imageUrl) ?>" alt="Img" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                                <div style="min-width: 0; overflow: hidden;">
                                                    <h6 class="mb-0 fw-bold text-dark text-truncate"><?= htmlspecialchars($prod->name) ?></h6>
                                                    <small class="text-muted text-truncate d-block"><?= htmlspecialchars($prod->description) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success text-truncate"><?= $prod->getFormattedCashPrice() ?></td>

                                        <td class="text-center">
                                            <?php if ($prod->stockQuantity > 0): ?>
                                                <span class="badge bg-light text-dark border"><?= $prod->stockQuantity ?> un.</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Esgotado</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($prod->active): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 border border-success" style="font-size: 0.75rem;">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill px-2" style="font-size: 0.75rem;">Inativo</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="pe-4 text-end text-nowrap">
                                            <div class="d-flex justify-content-end gap-1">
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
                                                        "category_id" => $prod->categoryId ?? 1,
                                                        "stock_quantity" => $prod->stockQuantity
                                                    ]) ?>)'>
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>

                                                <form action="/admin/products/toggle" method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= $prod->id ?>">
                                                    <button type="submit" class="btn btn-sm border-0 <?= $prod->active ? 'text-warning' : 'text-success' ?>"
                                                        title="<?= $prod->active ? 'Desativar Produto' : 'Reativar Produto' ?>">
                                                        <i class="fa-solid <?= $prod->active ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                                    </button>
                                                </form>

                                                <form action="/admin/products/delete" method="POST" class="d-inline" onsubmit="return confirm('ATENÇÃO: Isso excluirá o produto permanentemente. Para apenas esconder da loja, use o botão de olho. Continuar?');">
                                                    <input type="hidden" name="id" value="<?= $prod->id ?>">
                                                    <button type="submit" class="btn btn-sm border-0 text-danger" title="Excluir Definitivamente">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        Nenhum produto encontrado.
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/admin/products/create" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Nome</label>
                                <input type="text" name="name" class="form-control" required>
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
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Preço à Vista</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">R$</span>
                                    <input type="text" name="price_cash" class="form-control border-start-0 ps-1" required oninput="formatMoney(this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Preço a Prazo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">R$</span>
                                    <input type="text" name="price_installments" class="form-control border-start-0 ps-1" required oninput="formatMoney(this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Estoque Atual</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-boxes-stacked text-secondary"></i></span>
                                    <input type="number" name="stock_quantity" class="form-control border-start-0 ps-1" required value="0" min="0">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Imagem</label>
                                <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/webp">
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-buy fw-bold py-2 rounded-pill">Salvar Produto</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/admin/products/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Nome</label>
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

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Preço à Vista</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">R$</span>
                                    <input type="text" name="price_cash" id="edit_price_cash" class="form-control border-start-0 ps-1" required oninput="formatMoney(this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Preço a Prazo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">R$</span>
                                    <input type="text" name="price_installments" id="edit_price_installments" class="form-control border-start-0 ps-1" required oninput="formatMoney(this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Estoque Atual</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-boxes-stacked text-secondary"></i></span>
                                    <input type="number" name="stock_quantity" id="edit_stock_quantity" class="form-control border-start-0 ps-1" required min="0">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Alterar Imagem</label>
                                <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/webp">
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-buy fw-bold py-2 rounded-pill">Atualizar Produto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
