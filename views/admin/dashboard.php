<?php
$pageTitle = 'Painel Administrativo | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container my-5 flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-brand fw-bold"><i class="fa-solid fa-gauge-high me-2"></i>Dashboard</h1>
                <p class="text-secondary small">Bem-vindo ao painel de controle, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrador') ?></p>
            </div>
            <span class="badge bg-primary fs-6"><?= strtoupper($_SESSION['user_role'] ?? '') ?></span>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-users-gear fa-3x text-accent"></i>
                        </div>
                        <h5 class="card-title fw-bold">Funcionários</h5>
                        <p class="card-text text-muted small">Gerencie acessos, cadastre novos colaboradores e defina cargos.</p>
                        <a href="/admin/employees" class="btn btn-outline-primary w-100 fw-bold stretched-link">
                            Gerenciar Equipe
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-box-open fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Produtos</h5>
                        <p class="card-text text-muted small">Cadastre novos produtos, edite preços e controle o estoque.</p>
                        <a href="/admin/products" class="btn btn-outline-primary w-100 fw-bold stretched-link">
                            Catálogo de Produtos
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-tags fa-3x text-danger"></i>
                        </div>
                        <h5 class="card-title fw-bold">Promoções</h5>
                        <p class="card-text text-muted small">Crie campanhas de desconto e defina datas de validade.</p>
                        <a href="/admin/promotions" class="btn btn-outline-primary w-100 fw-bold stretched-link">
                            Gerenciar Ofertas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h4 class="h5 fw-bold text-secondary mb-3">Visão Geral do Sistema</h4>
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
                <i class="fa-solid fa-circle-info me-3 fa-lg"></i>
                <div>
                    <strong>Dica:</strong> Para cadastrar um novo funcionário, certifique-se de ter criado os cargos no banco de dados previamente.
                </div>
            </div>
        </div>

    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <style>
        .hover-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
    </style>
</body>
</html>
