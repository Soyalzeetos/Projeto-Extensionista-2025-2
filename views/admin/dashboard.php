<?php
$pageTitle = 'Painel Administrativo | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require __DIR__ . '/../partials/header_admin.php'; ?>

    <main class="container my-5 flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-brand fw-bold"><i class="fa-solid fa-gauge-high me-2"></i>Dashboard</h1>
                <p class="text-secondary small">Bem-vindo ao painel de controle, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrador') ?></p>
            </div>
            <span class="badge bg-primary fs-6"><?= strtoupper($_SESSION['user_role'] ?? '') ?></span>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-users-gear fa-3x text-accent"></i>
                        </div>
                        <h5 class="card-title fw-bold">Funcionários</h5>
                        <p class="card-text text-muted small">Gerencie acessos e colaboradores.</p>
                        <a href="/admin/employees" class="btn btn-outline-primary w-100 fw-bold stretched-link">
                            Equipe
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-layer-group fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Categorias</h5>
                        <p class="card-text text-muted small">Organize os departamentos da loja.</p>
                        <a href="/admin/categories" class="btn btn-outline-primary w-100 fw-bold stretched-link">
                            Departamentos
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-box-open fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Produtos</h5>
                        <p class="card-text text-muted small">Controle de estoque e preços.</p>
                        <a href="/admin/products" class="btn btn-outline-primary w-100 fw-bold stretched-link">
                            Catálogo
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-tags fa-3x text-danger"></i>
                        </div>
                        <h5 class="card-title fw-bold">Promoções</h5>
                        <p class="card-text text-muted small">Crie campanhas e ofertas.</p>
                        <a href="/admin/promotions" class="btn btn-outline-primary w-100 fw-bold stretched-link">
                            Ofertas
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-clipboard-list fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title fw-bold">Pedidos</h5>
                        <p class="card-text text-muted small">Acompanhe vendas e entregas.</p>
                        <a href="/admin/orders" class="btn btn-outline-primary w-100 fw-bold stretched-link">
                            Vendas
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
                    <strong>Dica:</strong> Mantenha as categorias atualizadas para facilitar a navegação dos clientes na loja.
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
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }
    </style>
</body>

</html>
