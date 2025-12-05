<header>
    <nav class="navbar-custom container-fluid py-3">
        <div class="row align-items-center">

            <div class="col-3 col-lg-2 text-center">
                <a href="/" aria-label="Home">
                    <img class="logo img-fluid" src="assets/img/ui/logo.webp" alt="Center Ferramentas" />
                </a>
            </div>

            <div class="col-6 col-lg-8">
                <form role="search" class="search-class">
                    <label for="campo-busca" class="visually-hidden">Buscar</label>
                    <div class="input-group">
                        <input class="form-control border-0 rounded-start-pill py-2 ps-4" type="search" id="campo-busca"
                            placeholder="O que você precisa para sua obra hoje?" />
                        <button class="btn btn-light border-0 rounded-end-pill pe-4" type="button">
                            <i class="fa-solid fa-magnifying-glass text-secondary"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-3 col-lg-2 d-flex justify-content-center gap-3">
                <button class="btn border-0 p-0 position-relative" aria-label="Carrinho">
                    <img class="icon-nav" src="assets/img/ui/icone-carrinho.webp" alt="Carrinho" aria-hidden="true" />
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">Novos itens</span>
                    </span>
                </button>
                <button class="btn border-0 p-0" aria-label="Perfil">
                    <img class="icon-nav" src="assets/img/ui/icone-perfil.webp" alt="Perfil" aria-hidden="true" />
                </button>
            </div>

            <div class="col-lg-12 d-flex justify-content-center gap-3 mt-3">
                <div class="d-flex gap-3">

                    <div class="dropdown">
                        <button class="dropbtn">
                            Categorias <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <a href="/?category_id=<?= $category->id ?>" class="text-center">
                                        <?= htmlspecialchars($category->name) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="d-block text-center p-2 text-muted small">Carregando...</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="dropbtn">
                            Orçamentos <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="#" class="text-center">Solicitar Cotação</a>
                            <a href="#" class="text-center">Vendas Corporativas</a>
                            <a href="#" class="text-center">Enviar Lista de Material</a>
                            <hr class="dropdown-divider my-0 border-secondary opacity-25">
                            <a href="#" class="text-center fw-bold">
                                <i class="fa-brands fa-whatsapp text-success"></i> Falar no WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="dropbtn">
                            Ajuda <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="#" class="text-center">Trocas e Devoluções</a>
                            <a href="#" class="text-center">Fale Conosco</a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </nav>
</header>
