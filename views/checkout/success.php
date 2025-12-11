<?php
$pageTitle = 'Pedido Enviado | Center Ferramentas';
require __DIR__ . '/../partials/head.php';
?>

<body class="d-flex flex-column min-vh-100 bg-light">
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main class="container d-flex flex-column justify-content-center align-items-center flex-grow-1 my-5">
        <div class="text-center p-5 rounded-4 shadow-sm bg-white" style="max-width: 650px;">
            <div class="mb-4">
                <i class="fa-regular fa-paper-plane fa-6x text-primary opacity-75"></i>
            </div>

            <h1 class="h2 fw-bold text-brand mb-3">Solicitação Recebida!</h1>

            <p class="lead text-dark mb-4">
                Obrigado, <strong><?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?></strong>!
                Seu pedido <strong>#<?= $orderId ?></strong> foi enviado para nossa central de vendas.
            </p>

            <div class="bg-light p-4 rounded-4 mb-4 text-start">
                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-circle-info me-2 text-accent"></i>Próximos Passos:</h6>
                <ol class="mb-0 ps-3 text-secondary small">
                    <li class="mb-2">Nossa equipe verificará a disponibilidade imediata dos itens.</li>
                    <li class="mb-2">Entraremos em contato via <strong>WhatsApp</strong> ou <strong>Telefone</strong> (cadastrados na sua conta).</li>
                    <li>Finalizaremos o pagamento e combinaremos a entrega ou retirada.</li>
                </ol>
            </div>

            <p class="text-muted small mb-4">
                Se preferir agilizar, você pode nos chamar agora mesmo informando o número do pedido.
            </p>

            <div class="d-grid gap-2 d-sm-flex justify-content-center">
                <a href="/" class="btn btn-outline-dark px-4 fw-bold">Continuar na Loja</a>
                <a href="https://wa.me/5534991975188?text=Olá, acabei de fazer o pedido #<?= $orderId ?> e gostaria de agilizar o atendimento."
                   target="_blank"
                   class="btn btn-success px-4 fw-bold">
                    <i class="fa-brands fa-whatsapp me-2"></i> Falar com Vendedor
                </a>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
