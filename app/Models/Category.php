<?php
/**
 * app/Models/Category.php
 * [C2] Xử lý nghiệp vụ danh mục sản phẩm (thêm/sửa/xóa)
 * [C4] Dùng Prepared Statement chống SQL Injection
 */

namespace App\Models;

use PDO;

class Category
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?object
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();
        return $category ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO categories (name, slug, description, icon) 
                VALUES (:name, :slug, :description, :icon)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? null,
            'icon'        => $data['icon'] ?? null
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE categories 
                SET name = :name, slug = :slug, description = :description, icon = :icon,
                    updated_at = NOW() 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'          => $id,
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? null,
            'icon'        => $data['icon'] ?? null
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
