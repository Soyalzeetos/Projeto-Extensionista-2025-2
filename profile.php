<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | Qualidade e Confiança</title>

    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link rel="apple-touch-icon" href="assets/img/ui/apple-touch-icon.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
<?php
// Caminho para o arquivo do banco (se não existir, o PHP cria sozinho!)
$caminhoBanco = __DIR__ . '/loja.db';

try {

    $pdo = new PDO('sqlite:' . $caminhoBanco);
    

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectado ao SQLite com sucesso, Pablo!";
    

    $sql = "CREATE TABLE IF NOT EXISTS ferramentas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT,
                preco REAL
            )";
    $pdo->exec($sql);
    echo "<br>Tabela de ferramentas verificada/criada.";

} catch (PDOException $e) {
    echo "Ops, deu ruim na conexão: " . $e->getMessage();
}
?>
</body>

</html>