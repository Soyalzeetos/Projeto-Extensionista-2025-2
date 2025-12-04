<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Query atualizada: Busca dados da tabela products e junta com categories e departments
$query = "
    SELECT
        p.id,
        p.name,
        p.description,
        p.price,
        p.image_path,
        p.is_promo,
        c.name as category_name,
        d.name as department_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    JOIN departments d ON c.department_id = d.id
    ORDER BY p.id ASC
";

$stmt = $db->prepare($query);
$stmt->execute();

$products = array();

// Pega a URL do .env ou usa localhost como fallback
$base_url = getenv('APP_URL') !== false ? getenv('APP_URL') : "http://localhost:8000";
if (substr($base_url, -1) != '/') {
    $base_url .= '/';
}

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $product_item = array(
        "id" => $id,
        "name" => $name,
        "description" => $description,
        "price" => $price,
        "image" => $base_url . $image_path,
        "is_promo" => (bool)$is_promo,
        // Agora retornamos o nome vindo da tabela de categorias e departamento
        "category" => $category_name,
        "department" => $department_name
    );
    array_push($products, $product_item);
}

echo json_encode($products);
?>
