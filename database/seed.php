<?php
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$adminName  = getenv('APP_ADMIN_NAME');
$adminEmail = getenv('APP_ADMIN_EMAIL');
$adminPass  = getenv('APP_ADMIN_PASS');

try {
    $maxTries = 10;
    $pdo = null;

    for ($i = 0; $i < $maxTries; $i++) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            break;
        } catch (PDOException $e) {
            echo "[WAIT] Waiting for database... (" . ($i + 1) . "/$maxTries)\n";
            sleep(3);
        }
    }

    if (!$pdo) {
        throw new Exception("Could not connect to the database after multiple attempts.");
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);

    if ($stmt->fetch()) {
        echo "[SKIP] Administrator '{$adminEmail}' already exists.\n";
        exit(0);
    }

    $pdo->beginTransaction();

    $passHash = password_hash($adminPass, PASSWORD_DEFAULT);
    $stmtUser = $pdo->prepare("INSERT INTO users (name, email, password_hash, active, created_at) VALUES (?, ?, ?, 1, NOW())");
    $stmtUser->execute([$adminName, $adminEmail, $passHash]);
    $userId = $pdo->lastInsertId();

    $stmtRole = $pdo->prepare("SELECT id FROM roles WHERE slug = 'admin'");
    $stmtRole->execute();
    $roleId = $stmtRole->fetchColumn();

    if ($roleId) {
        $stmtEmp = $pdo->prepare("INSERT INTO employees (user_id, role_id, registration_number, hire_date, department) VALUES (?, ?, 'ADM-001', NOW(), 'Board')");
        $stmtEmp->execute([$userId, $roleId]);
        echo "[SUCCESS] Administrator created via Environment Variables.\n";
    } else {
        echo "[ERROR] Role 'admin' not found. Run init.sql first.\n";
    }

    echo "[INFO] Starting product image update...\n";

    $productImages = [
        1 => __DIR__ . '/../public/assets/img/produtos/serra-circular-de-bancada.webp',
        2 => __DIR__ . '/../public/assets/img/produtos/zoom-1712-A7019.webp',
        3 => __DIR__ . '/../public/assets/img/produtos/martelete-makita.webp',
        4 => __DIR__ . '/../public/assets/img/produtos/arame-farpado.webp',
        5 => __DIR__ . '/../public/assets/img/produtos/chave-sextavada.webp',
        6 => __DIR__ . '/../public/assets/img/produtos/chave-inglesa.webp',
        7 => __DIR__ . '/../public/assets/img/produtos/serra-marmore-makita.webp',
        8 => __DIR__ . '/../public/assets/img/produtos/alicate-universal.webp',
        9 => __DIR__ . '/../public/assets/img/produtos/alicate-de-pressao.webp',
        10 => __DIR__ . '/../public/assets/img/produtos/kit-chave-allen.webp',
    ];

    $stmtUpdateImg = $pdo->prepare("UPDATE products SET image_data = ?, image_mime = ? WHERE id = ?");

    foreach ($productImages as $id => $filePath) {
        if (file_exists($filePath)) {
            $binaryData = file_get_contents($filePath);
            $base64Data = base64_encode($binaryData);
            $mimeType = mime_content_type($filePath);

            $stmtUpdateImg->execute([$base64Data, $mimeType, $id]);
            echo "[SUCCESS] Image updated for product ID: $id\n";
        } else {
            echo "[WARNING] Image not found for product ID: $id ($filePath)\n";
        }
    }

    $pdo->commit();
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, "[ERROR] " . $e->getMessage() . "\n");
}
