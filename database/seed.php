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
            echo "[WAIT] Aguardando banco de dados... (" . ($i + 1) . "/$maxTries)\n";
            sleep(3);
        }
    }

    if (!$pdo) {
        throw new Exception("Não foi possível conectar ao banco após várias tentativas.");
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);

    if ($stmt->fetch()) {
        echo "[SKIP] O Administrador '{$adminEmail}' já existe.\n";
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
        $stmtEmp = $pdo->prepare("INSERT INTO employees (user_id, role_id, registration_number, hire_date, department) VALUES (?, ?, 'ADM-001', NOW(), 'Diretoria')");
        $stmtEmp->execute([$userId, $roleId]);
        echo "[SUCCESS] Administrador criado via Variáveis de Ambiente.\n";
    } else {
        echo "[ERROR] Role 'admin' não encontrada. Rode o init.sql primeiro.\n";
    }

    $pdo->commit();
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, "[ERROR] " . $e->getMessage() . "\n");
}
