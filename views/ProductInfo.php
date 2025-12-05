<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto->nome) ?> | Center Ferramentas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <header>
        <nav class="navbar-custom container-fluid py-3">
            <div class="row align-items-center">
                <div class="col-3 col-lg-2 text-center">
                    <a href="/" aria-label="Home">
                        <img class="logo img-fluid" src="assets/img/ui/logo.webp" alt="Center Ferramentas">
                    </a>
                </div>
                <div class="col-6 col-lg-8">
                    <form role="search" class="search-class">
                        <div class="input-group">
                            <input class="form-control border-0 rounded-start-pill py-2 ps-4" type="search" placeholder="Buscar...">
                            <button class="btn btn-light border-0 rounded-end-pill pe-4" type="button">
                                <i class="fa-solid fa-magnifying-glass text-secondary"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-3 col-lg-2 d-flex justify-content-center gap-3">
                    <a href="/" class="btn btn-outline-light rounded-pill">Voltar</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5 flex-grow-1">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 bg-white d-flex align-items-center justify-content-center p-5">
                    <img src="<?= htmlspecialchars($produto->imagemUrl) ?>"
                        class="img-fluid"
                        style="max-height: 400px; object-fit: contain;"
                        alt="<?= htmlspecialchars($produto->nome) ?>">
                </div>

                <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center bg-light">
                    <h6 class="text-uppercase text-muted fw-bold mb-2">Detalhes do Produto</h6>
                    <h1 class="display-6 fw-bold text-primary mb-3"><?= htmlspecialchars($produto->nome) ?></h1>

                    <p class="lead text-secondary mb-4"><?= htmlspecialchars($produto->descricao) ?></p>

                    <hr class="my-4">

                    <div class="d-flex align-items-end mb-4">
                        <div>
                            <span class="d-block text-muted small text-decoration-line-through">De: R$ <?= number_format($produto->preco * 1.2, 2, ',', '.') ?></span>
                            <span class="fs-1 fw-bold text-dark"><?= $produto->getFormattedPrice() ?></span>
                            <span class="text-success small fw-bold ms-2">à vista no Pix</span>
                        </div>
                    </div>

                    <div class="d-grid gap-3 d-md-block">
                        <button class="btn btn-primary btn-lg rounded-pill px-5 fw-bold">
                            <i class="fa-solid fa-cart-shopping me-2"></i> Adicionar ao Carrinho
                        </button>
                        <a href="/" class="btn btn-outline-dark btn-lg rounded-pill px-4">
                            Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-0 fw-bold">Center Ferramentas &copy; 2025</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
