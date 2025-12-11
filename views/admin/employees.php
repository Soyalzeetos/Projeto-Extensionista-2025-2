<?php
$pageTitle = 'Gerenciar Equipe | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container my-5 flex-grow-1">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-brand fw-bold"><i class="fa-solid fa-users-gear me-2"></i>Gerenciar Equipe</h1>
                <p class="text-secondary small">Cadastre colaboradores e defina suas funções.</p>
            </div>
            <button type="button" class="btn  fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newEmployeeModal">
                <i class="fa-solid fa-plus me-2"></i>Novo Funcionário
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Operação realizada com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Funcionário</th>
                                <th class="py-3">Matrícula</th>
                                <th class="py-3">Cargo (Role)</th>
                                <th class="py-3">E-mail</th>
                                <th class="pe-4 py-3 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($employees)): ?>
                                <?php foreach ($employees as $emp): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <?= strtoupper(substr($emp['name'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($emp['name']) ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($emp['registration_number']) ?></span></td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?= htmlspecialchars($emp['role_name']) ?>
                                            </span>
                                        </td>
                                        <td class="text-secondary"><?= htmlspecialchars($emp['email']) ?></td>
                                        <td class="pe-4 text-end">
                                            <button class="btn btn-sm btn-outline-secondary me-1" title="Editar (Em breve)">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
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
                    <h5 class="modal-title fw-bold">Novo Colaborador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/admin/employees/store" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nome Completo</label>
                            <input type="text" name="name" class="form-control" required placeholder="Ex: Maria Souza">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">E-mail Corporativo</label>
                            <input type="email" name="email" class="form-control" required placeholder="nome@centerferramentas.com">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Senha Inicial</label>
                                <input type="password" name="password" class="form-control" required placeholder="******">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Cargo / Permissões</label>
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

                        <div class="alert alert-info py-2 small border-0">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            O novo usuário terá acesso às permissões vinculadas ao cargo selecionado.
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold py-2 ">
                                Cadastrar Funcionário
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
