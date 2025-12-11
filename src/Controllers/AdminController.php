<?php

namespace App\Controllers;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\EmployeeRepository;
use App\Repository\PromotionRepository;
use App\Domain\Product;

class AdminController
{
    public function __construct(
        private ProductRepository $productRepo,
        private CategoryRepository $categoryRepo,
        private EmployeeRepository $employeeRepo,
        private PromotionRepository $promotionRepo,
        private \App\Repository\OrderRepository $orderRepo
    ) {
        $this->ensureAdmin();
    }

    private function ensureAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $role = $_SESSION['user_role'] ?? '';
        if (!in_array($role, ['admin', 'sales_manager'])) {
            header('Location: /?error=unauthorized');
            exit;
        }
    }

    private function handleImageUpload(array $file): ?array
    {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                return null;
            }

            $content = file_get_contents($file['tmp_name']);
            return [
                'data' => base64_encode($content),
                'mime' => $file['type']
            ];
        }
        return null;
    }

    private function parseCurrency(?string $value): float
    {
        if (!$value) return 0.0;
        $clean = str_replace('.', '', $value);
        $clean = str_replace(',', '.', $clean);
        return (float) $clean;
    }

    public function dashboard(): void
    {
        require __DIR__ . '/../../views/admin/dashboard.php';
    }

    public function listEmployees(): void
    {
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /admin');
            return;
        }

        $employees = $this->employeeRepo->findAll();
        $roles = $this->employeeRepo->getAllRoles();
        require __DIR__ . '/../../views/admin/employees.php';
    }

    public function storeEmployee(): void
    {
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /admin');
            exit;
        }

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $roleId = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);

        if (!$name || !$email || !$password || !$roleId) {
            header('Location: /admin/employees?error=missing_fields');
            exit;
        }

        $success = $this->employeeRepo->createEmployee($name, $email, $password, $roleId);

        if ($success) {
            header('Location: /admin/employees?success=created');
        } else {
            header('Location: /admin/employees?error=creation_failed');
        }
        exit;
    }

    public function updateEmployee(): void
    {
        $this->ensureAdmin();

        $id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $roleId = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);
        $password = filter_input(INPUT_POST, 'password');

        if ($id && $name && $email && $roleId) {
            $pwd = !empty($password) ? $password : null;
            $this->employeeRepo->updateEmployee($id, $name, $email, $roleId, $pwd);
            header('Location: /admin/employees?success=updated');
        } else {
            header('Location: /admin/employees?error=missing_fields');
        }
    }

    public function toggleEmployeeStatus(): void
    {
        $this->ensureAdmin();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id === $_SESSION['user_id']) {
            header('Location: /admin/employees?error=cannot_disable_self');
            return;
        }

        if ($id) {
            $this->employeeRepo->toggleStatus($id);
        }
        header('Location: /admin/employees?success=status_changed');
    }

    public function deleteEmployee(): void
    {
        $this->ensureAdmin();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id === $_SESSION['user_id']) {
            header('Location: /admin/employees?error=cannot_delete_self');
            return;
        }

        if ($id) {
            $this->employeeRepo->delete($id);
        }
        header('Location: /admin/employees?success=deleted');
    }

    public function listProducts(): void
    {
        $products = $this->productRepo->findAllForAdmin();
        $categories = $this->categoryRepo->findAll();
        require __DIR__ . '/../../views/admin/products.php';
    }

    public function storeProduct(): void
    {
        $this->ensureAdmin();

        $name = filter_input(INPUT_POST, 'name');
        $desc = filter_input(INPUT_POST, 'description');
        $catId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $stock = filter_input(INPUT_POST, 'stock_quantity', FILTER_VALIDATE_INT);

        $priceCash = $this->parseCurrency($_POST['price_cash'] ?? null);
        $priceInst = $this->parseCurrency($_POST['price_installments'] ?? null);

        if ($name && $priceCash && $catId) {
            $imgData = null;
            $imgMime = null;

            if (isset($_FILES['image'])) {
                $processed = $this->handleImageUpload($_FILES['image']);
                if ($processed) {
                    $imgData = $processed['data'];
                    $imgMime = $processed['mime'];
                }
            }

            $product = new Product(0, $name, $desc ?? '', $priceCash, $priceInst ?: $priceCash, '', false, $stock ?? 0, true);

            $this->productRepo->create($product, $catId, $imgData, $imgMime);
            header('Location: /admin/products?success=created');
        } else {
            header('Location: /admin/products?error=missing_fields');
        }
    }

    public function updateProduct(): void
    {
        $this->ensureAdmin();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name');
        $desc = filter_input(INPUT_POST, 'description');
        $catId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $stock = filter_input(INPUT_POST, 'stock_quantity', FILTER_VALIDATE_INT);

        $priceCash = $this->parseCurrency($_POST['price_cash'] ?? null);
        $priceInst = $this->parseCurrency($_POST['price_installments'] ?? null);

        if ($id && $name && $priceCash && $catId) {
            $imgData = null;
            $imgMime = null;

            if (isset($_FILES['image'])) {
                $processed = $this->handleImageUpload($_FILES['image']);
                if ($processed) {
                    $imgData = $processed['data'];
                    $imgMime = $processed['mime'];
                }
            }

            $this->productRepo->update($id, $name, $desc ?? '', $priceCash, $priceInst ?: $priceCash, $catId, $stock ?? 0, $imgData, $imgMime);
            header('Location: /admin/products?success=updated');
        } else {
            header('Location: /admin/products?error=update_failed');
        }
    }

    public function toggleProductStatus(): void
    {
        $this->ensureAdmin();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $this->productRepo->toggleStatus($id);
            header('Location: /admin/products?success=status_changed');
        } else {
            header('Location: /admin/products?error=invalid_id');
        }
    }

    public function deleteProduct(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $this->productRepo->delete($id);
        }
        header('Location: /admin/products?success=deleted');
    }

    public function listCategories(): void
    {
        $this->ensureAdmin();
        $categories = $this->categoryRepo->findAll();
        require __DIR__ . '/../../views/admin/categories.php';
    }

    public function storeCategory(): void
    {
        $this->ensureAdmin();

        $name = filter_input(INPUT_POST, 'name');
        $desc = filter_input(INPUT_POST, 'description') ?? '';

        if ($name) {
            $this->categoryRepo->create($name, $desc);
            header('Location: /admin/categories?success=created');
        } else {
            header('Location: /admin/categories?error=missing_fields');
        }
    }

    public function updateCategory(): void
    {
        $this->ensureAdmin();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name');
        $desc = filter_input(INPUT_POST, 'description') ?? '';

        if ($id && $name) {
            $this->categoryRepo->update($id, $name, $desc);
            header('Location: /admin/categories?success=updated');
        } else {
            header('Location: /admin/categories?error=update_failed');
        }
    }

    public function deleteCategory(): void
    {
        $this->ensureAdmin();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $this->categoryRepo->delete($id);
            header('Location: /admin/categories?success=deleted');
        } else {
            header('Location: /admin/categories?error=delete_failed');
        }
    }

    public function listPromotions(): void
    {
        $promotions = $this->promotionRepo->findAll();
        $products = $this->productRepo->findAllForAdmin();
        require __DIR__ . '/../../views/admin/promotions.php';
    }

    public function storePromotion(): void
    {
        $this->ensureAdmin();

        $name = filter_input(INPUT_POST, 'name');
        $discount = filter_input(INPUT_POST, 'discount', FILTER_VALIDATE_FLOAT);
        $start = filter_input(INPUT_POST, 'start_date');
        $end = filter_input(INPUT_POST, 'end_date');
        $productIds = $_POST['products'] ?? [];

        if ($name && $discount && $start && $end) {
            $this->promotionRepo->create($name, $discount, $start, $end, $productIds);
            header('Location: /admin/promotions?success=created');
        } else {
            header('Location: /admin/promotions?error=missing_fields');
        }
    }

    public function updatePromotion(): void
    {
        $this->ensureAdmin();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name');
        $discount = filter_input(INPUT_POST, 'discount', FILTER_VALIDATE_FLOAT);
        $start = filter_input(INPUT_POST, 'start_date');
        $end = filter_input(INPUT_POST, 'end_date');
        $productIds = $_POST['products'] ?? [];

        if ($id && $name && $discount) {
            $this->promotionRepo->update($id, $name, $discount, $start, $end, $productIds);
            header('Location: /admin/promotions?success=updated');
        } else {
            header('Location: /admin/promotions?error=update_failed');
        }
    }

    public function togglePromotionStatus(): void
    {
        $this->ensureAdmin();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $this->promotionRepo->toggleStatus($id);
            header('Location: /admin/promotions?success=status_changed');
        }
    }

    public function deletePromotion(): void
    {
        $this->ensureAdmin();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $this->promotionRepo->delete($id);
            header('Location: /admin/promotions?success=deleted');
        }
    }

    public function listOrders(): void
    {
        $orders = $this->orderRepo->findAll();
        require __DIR__ . '/../../views/admin/orders.php';
    }

    public function updateOrderStatus(): void
    {
        $this->ensureAdmin();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status');

        $allowed = ['pending', 'awaiting_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];

        if ($id && in_array($status, $allowed)) {
            $this->orderRepo->updateStatus($id, $status);
            header('Location: /admin/orders?success=updated');
        } else {
            header('Location: /admin/orders?error=invalid_status');
        }
    }
}
