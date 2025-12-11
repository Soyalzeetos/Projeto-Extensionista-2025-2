<header class="navbar-custom border-bottom shadow-sm" style="height: 70px;">
    <div class="container h-100 d-flex justify-content-between align-items-center">

        <a href="/admin/dashboard" class="text-decoration-none d-flex align-items-center gap-3" title="Voltar ao Dashboard">
            <img src="/assets/img/ui/logo.webp" alt="Center Ferramentas" style="height: 40px;">
            <span class="badge bg-white text-brand text-uppercase letter-spacing-2 d-none d-md-block">Admin</span>
        </a>

        <div class="d-flex align-items-center gap-3">

            <a href="/" target="_blank" class="btn btn-light border-0 btn-sm px-3 d-none d-sm-flex align-items-center gap-2 text-brand fw-bold" title="Ver Loja">
                <i class="fa-solid fa-store"></i> <span class="small">Ver Loja</span>
            </a>

            <div class="dropdown">
                <button class="btn border-0 p-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-white text-brand fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2 p-2 rounded-3" style="min-width: 200px;">
                    <li class="px-2 py-1">
                        <small class="text-muted d-block">Logado como</small>
                        <span class="fw-bold text-dark text-truncate d-block" style="max-width: 180px;">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
                        </span>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <a class="dropdown-item rounded-2 text-danger fw-bold d-flex align-items-center gap-2 py-2" href="/logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Sair do Sistema
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
