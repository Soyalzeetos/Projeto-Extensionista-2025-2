<?php
$pageTitle = 'Gerenciar Categorias | Center Ferramentas';
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
                    <h1 class="h3 mb-0 text-brand fw-bold"><i class="fa-solid fa-layer-group me-2 text-primary"></i>Categorias</h1>
                    <p class="text-secondary small mb-0">Organize os departamentos da loja.</p>
                </div>
            </div>

            <button type="button" class="btn btn-buy fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                <i class="fa-solid fa-plus me-2"></i>Nova Categoria
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
                                <th class="ps-4 py-3 text-secondary small text-uppercase">Nome</th>
                                <th class="py-3 text-secondary small text-uppercase">Descrição</th>
                                <th class="pe-4 py-3 text-end text-secondary small text-uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($cat->name) ?></td>
                                        <td class="text-secondary small"><?= htmlspecialchars($cat->description ?? '-') ?></td>
                                        <td class="pe-4 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-sm btn-outline-secondary border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCategoryModal"
                                                    onclick='populateCategoryEdit(<?= json_encode([
                                                                                        "id" => $cat->id,
                                                                                        "name" => $cat->name,
                                                                                        "description" => $cat->description
                                                                                    ]) ?>)'>
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>

                                                <form action="/admin/categories/delete" method="POST" class="d-inline" onsubmit="return confirm('Excluir esta categoria?');">
                                                    <input type="hidden" name="id" value="<?= $cat->id ?>">
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
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        Nenhuma categoria cadastrada.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="newCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-brand">Nova Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <form action="/admin/categories/store" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nome</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Descrição</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-buy w-100 fw-bold rounded-pill">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-brand">Editar Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <form action="/admin/categories/update" method="POST">
                        <input type="hidden" name="id" id="cat_edit_id">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nome</label>
                            <input type="text" name="name" id="cat_edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Descrição</label>
                            <textarea name="description" id="cat_edit_description" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-buy w-100 fw-bold rounded-pill">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>
