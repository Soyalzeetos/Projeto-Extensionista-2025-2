<?php
$pageTitle = 'Gerenciar Equipe | Center Ferramentas';
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
                    <h1 class="h3 mb-0 text-brand fw-bold"><i class="fa-solid fa-users-gear me-2 text-accent"></i>Gerenciar Equipe</h1>
                    <p class="text-secondary small mb-0">Cadastre colaboradores e defina suas funções.</p>
                </div>
            </div>

            <button type="button" class="btn btn-buy fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newEmployeeModal">
                <i class="fa-solid fa-plus me-2"></i>Novo Funcionário
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?php
                echo match ($_GET['success']) {
                    'created' => 'Funcionário cadastrado com sucesso!',
                    'updated' => 'Dados atualizados com sucesso!',
                    'status_changed' => 'Status do usuário alterado com sucesso!',
                    'deleted' => 'Funcionário removido com sucesso!',
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
                switch ($_GET['error']) {
                    case 'missing_fields':
                        echo 'Por favor, preencha todos os campos obrigatórios.';
                        break;
                    case 'creation_failed':
                        echo 'Erro ao cadastrar. Verifique se o e-mail já existe.';
                        break;
                    case 'cannot_disable_self':
                        echo 'Ação negada: Você não pode desativar seu próprio usuário.';
                        break;
                    case 'cannot_delete_self':
                        echo 'Ação negada: Você não pode excluir seu próprio usuário.';
                        break;
                    default:
                        echo 'Ocorreu um erro inesperado.';
                }
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
                                <th class="ps-4 py-3 text-secondary small text-uppercase">Funcionário</th>
                                <th class="py-3 text-secondary small text-uppercase">Matrícula</th>
                                <th class="py-3 text-secondary small text-uppercase">Cargo</th>
                                <th class="py-3 text-secondary small text-uppercase">Status</th>
                                <th class="py-3 text-secondary small text-uppercase">E-mail</th>
                                <th class="pe-4 py-3 text-end text-secondary small text-uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($employees)): ?>
                                <?php foreach ($employees as $emp): ?>
                                    <tr class="<?= $emp['active'] ? '' : 'opacity-50 bg-light' ?>">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-brand rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                                    <?= strtoupper(substr($emp['name'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($emp['name']) ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-secondary border"><?= htmlspecialchars($emp['registration_number']) ?></span></td>
                                        <td>
                                            <span class="badge role-badge-background">
                                                <?= htmlspecialchars($emp['role_name']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($emp['active']): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 border border-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill px-3">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-secondary small"><?= htmlspecialchars($emp['email']) ?></td>
                                        <td class="pe-4 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-sm btn-outline-secondary border-0"
                                                    title="Editar"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editEmployeeModal"
                                                    onclick="populateEditModal(<?= htmlspecialchars(json_encode($emp)) ?>)">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>

                                                <form action="/admin/employees/toggle" method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                                    <button type="submit" class="btn btn-sm border-0 <?= $emp['active'] ? 'text-warning' : 'text-success' ?>"
                                                        title="<?= $emp['active'] ? 'Desativar acesso' : 'Reativar acesso' ?>">
                                                        <i class="fa-solid <?= $emp['active'] ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                                    </button>
                                                </form>

                                                <form action="/admin/employees/delete" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza? Isso removerá permanentemente o histórico deste funcionário.');">
                                                    <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                                    <button type="submit" class="btn btn-sm border-0 text-danger" title="Excluir Permanentemente">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-users-slash fa-2x mb-3 text-secondary opacity-50"></i><br>
                                        Nenhum funcionário cadastrado além de você.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="newEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-brand">Novo Colaborador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/admin/employees/store" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-secondary"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 ps-0" required placeholder="Ex: Maria Souza">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">E-mail Corporativo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-secondary"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0" required placeholder="nome@centerferramentas.com">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Senha Inicial</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-secondary"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" required placeholder="******">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Cargo</label>
                                <select name="role_id" class="form-select" required>
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>">
                                            <?= htmlspecialchars($role['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-light border d-flex align-items-center p-3 rounded-3 mt-2">
                            <i class="fa-solid fa-circle-info text-accent me-3 fa-lg"></i>
                            <div class="small text-muted lh-sm">
                                O novo usuário receberá as permissões automaticamente baseadas no cargo selecionado acima.
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-buy fw-bold py-2 rounded-pill">
                                Cadastrar Funcionário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-brand">Editar Colaborador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/admin/employees/update" method="POST">
                        <input type="hidden" name="user_id" id="edit_user_id">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-secondary"></i></span>
                                <input type="text" name="name" id="edit_name" class="form-control border-start-0 ps-0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">E-mail Corporativo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-secondary"></i></span>
                                <input type="email" name="email" id="edit_email" class="form-control border-start-0 ps-0" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Nova Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-secondary"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="(Opcional)">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Cargo</label>
                                <select name="role_id" id="edit_role_id" class="form-select" required>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>">
                                            <?= htmlspecialchars($role['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-buy fw-bold py-2 rounded-pill">
                                Salvar Alterações
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
