<?php

use App\Config\Database;
use App\Repository\CartRepository;
use App\Core\Logger;

$cartItems = [];
$cartTotal = 0;
$cartQty = 0;

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $pdo = Database::getConnection();
    $cartRepo = new CartRepository($pdo);

    $sessionId = session_id();
    $userId = $_SESSION['user_id'] ?? null;

    $cartId = $cartRepo->getOrCreateCart($sessionId, $userId);
    $cartItems = $cartRepo->getCartItems($cartId);

    foreach ($cartItems as $item) {
        $cartTotal += $item['price'] * $item['quantity'];
        $cartQty += $item['quantity'];
    }
} catch (\Exception $e) {
    Logger::error("Error loading cart in header:" . $e->getMessage());
}
?>

<header>
    <nav class="navbar-custom container-fluid py-3 position-relative">
        <div class="row align-items-center">

            <div class="col-6 col-lg-2 text-start text-lg-center">
                <a href="/" aria-label="Home">
                    <img class="logo img-fluid" src="assets/img/ui/logo.webp" alt="Center Ferramentas" />
                </a>
            </div>

            <div class="col-lg-8 d-none d-lg-block">
                <form role="search" class="search-class">
                    <label for="desktop-search-input" class="visually-hidden">Buscar</label>
                    <div class="input-group">
                        <input class="form-control border-0 py-2 ps-4" type="search" id="desktop-search-input"
                            placeholder="O que você precisa para sua obra hoje?" />
                        <button class="btn btn-light border-0 pe-4" type="button">
                            <i class="fa-solid fa-magnifying-glass text-secondary"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-2 d-lg-none text-end">
                <button class="btn text-white p-0" type="button" onclick="toggleSearch()">
                    <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                </button>
            </div>

            <div class="col-4 col-lg-2 d-flex justify-content-end justify-content-lg-center gap-3 align-items-center">

                <div class="dropdown">
                    <button class="btn border-0 p-0 position-relative" type="button"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                            aria-label="Carrinho de Compras">
                        <img class="icon-nav" src="assets/img/ui/icone-carrinho.webp" alt="Carrinho" />

                        <span id="cart-badge-icon" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light <?= $cartQty === 0 ? 'd-none' : '' ?>" style="font-size: 0.7rem;">
                            <?= $cartQty ?>
                        </span>
                    </button>
                    <?php require __DIR__ . '/cart_dropdown.php'; ?>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <button class="btn border-0 p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Meu Perfil">
                            <img class="icon-nav" src="assets/img/ui/icone-perfil.webp" alt="Perfil" />
                        </button>

                        <div class="dropdown-menu dropdown-menu-end shadow p-3" style="min-width: 240px;">
                            <div class="text-center mb-3">
                                <span class="d-block text-secondary small">Bem-vindo(a),</span>
                                <span class="fw-bold fs-5 text-brand text-truncate d-block">
                                    <?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?>
                                </span>
                                <?php if (!empty($_SESSION['user_role'])): ?>
                                    <span class="badge bg-primary mt-1">
                                        <?= htmlspecialchars(strtoupper($_SESSION['user_role'])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="dropdown-divider"></div>
                            <?php if (!empty($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'sales_manager'])): ?>
                                <a class="dropdown-item py-2 fw-bold text-primary" href="/admin/dashboard">
                                    <i class="fa-solid fa-gauge-high me-2"></i> Painel Admin
                                </a>
                                <div class="dropdown-divider"></div>
                            <?php endif; ?>
                            <a class="dropdown-item py-2" href="#"><i class="fa-regular fa-user me-2 text-secondary"></i> Minha Conta</a>
                            <a class="dropdown-item py-2" href="/my-orders"><i class="fa-solid fa-box-open me-2 text-secondary"></i> Meus Pedidos</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger py-2" href="/logout"><i class="fa-solid fa-right-from-bracket me-2"></i> Sair</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="btn btn-header-login" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="fa-regular fa-user"></i> <span class="d-none d-lg-flex">Entrar</span>
                        </button>
                        <?php require __DIR__ . '/login_dropdown.php'; ?>
                    </div>
                <?php endif; ?>

            </div>

            <div class="col-12 col-lg-12 d-flex justify-content-center mt-3">
                <div class="d-flex gap-2 gap-md-3 flex-wrap justify-content-center">

                    <div class="dropdown">
                        <button class="dropbtn btn-departments">
                            <i class="fa-solid fa-bars"></i> <span class="d-none d-md-flex">DEPARTAMENTOS</span>
                        </button>
                        <div class="dropdown-content">
                            <?php
                            if (!isset($categories)) {
                                try {
                                    $catRepo = new \App\Repository\CategoryRepository($pdo);
                                    $categories = $catRepo->findAll();
                                } catch (Exception $e) {
                                    $categories = [];
                                }
                            }
                            ?>

                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <a href="/?category_id=<?= $category->id ?>" class="text-start px-4">
                                        <?= htmlspecialchars($category->name) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="d-block text-center p-2 text-muted small">Carregando...</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="dropbtn">Orçamentos <i class="fa fa-caret-down"></i></button>
                        <div class="dropdown-content">
                            <a target="_blank" href="https://wa.me/5534991975188" class="text-center">Solicitar Cotação</a>
                            <a href="#" class="text-center">Vendas Corporativas</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="dropbtn">Ajuda <i class="fa fa-caret-down"></i></button>
                        <div class="dropdown-content">
                            <a href="#" class="text-center">Trocas e Devoluções</a>
                            <a href="#" class="text-center">Fale Conosco</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div id="mobile-search-overlay" class="mobile-search-overlay d-none">
            <div class="container d-flex align-items-center h-100 gap-2">
                <form role="search" class="flex-grow-1">
                    <div class="input-group">
                        <input class="form-control border-0 py-2 ps-4" type="search" id="mobile-search-input"
                            placeholder="Buscar produtos..." />
                        <button class="btn btn-light border-0" type="button">
                            <i class="fa-solid fa-magnifying-glass text-secondary"></i>
                        </button>
                    </div>
                </form>
                <button class="btn text-white" onclick="toggleSearch()">
                    <i class="fa-solid fa-xmark fa-xl"></i>
                </button>
            </div>
        </div>

    </nav>
</header>
