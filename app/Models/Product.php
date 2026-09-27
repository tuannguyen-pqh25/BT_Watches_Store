<?php
/**
 * app/Models/Product.php
 * [C2] Xử lý nghiệp vụ sản phẩm
 * [C4] Dùng Prepared Statement chống SQL Injection
 */

namespace App\Models;

use PDO;

class Product
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(?int $limit = null, int $offset = 0, bool $onlyShow = false): array
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id ";
                
        if ($onlyShow) {
            $sql .= " WHERE p.status = 'show' ";
        }
        
        $sql .= " ORDER BY p.created_at DESC";
                
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số sản phẩm (dùng cho phân trang)
     */
    public function countAll(bool $onlyShow = false): int
    {
        $sql = "SELECT COUNT(*) FROM products";
        if ($onlyShow) {
            $sql .= " WHERE status = 'show'";
        }
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Đếm sản phẩm theo bộ lọc keyword + category (dùng cho phân trang khi search)
     */
    public function countByFilter(string $keyword = '', ?int $categoryId = null): int
    {
        $sql = "SELECT COUNT(*) FROM products p 
                WHERE p.status = 'show' 
                AND (p.name ILIKE :keyword OR p.description ILIKE :keyword OR p.brand ILIKE :keyword)";
        
        $params = ['keyword' => '%' . $keyword . '%'];
        
        if ($categoryId !== null) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getById(int $id): ?object
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    public function getBySlug(string $slug): ?object
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.slug = :slug
        ");
        $stmt->execute(['slug' => $slug]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    public function search(string $keyword, ?int $categoryId = null, ?int $limit = null, int $offset = 0): array
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'show' 
                AND (p.name ILIKE :keyword OR p.description ILIKE :keyword OR p.brand ILIKE :keyword)";
        
        $params = ['keyword' => '%' . $keyword . '%'];
        
        if ($categoryId !== null) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }
        
        $sql .= " ORDER BY p.created_at DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO products (category_id, name, slug, description, price, stock_quantity, image_path, brand, status) 
                VALUES (:category_id, :name, :slug, :description, :price, :stock_quantity, :image_path, :brand, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'category_id'    => $data['category_id'],
            'name'           => $data['name'],
            'slug'           => $data['slug'],
            'description'    => $data['description'] ?? null,
            'price'          => $data['price'],
            'stock_quantity' => $data['stock_quantity'] ?? 0,
            'image_path'     => $data['image_path'] ?? null,
            'brand'          => $data['brand'] ?? null,
            'status'         => $data['status'] ?? 'show'
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE products 
                SET category_id = :category_id, name = :name, slug = :slug, description = :description, 
                    price = :price, stock_quantity = :stock_quantity, image_path = :image_path, 
                    brand = :brand, status = :status, updated_at = NOW() 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'             => $id,
            'category_id'    => $data['category_id'],
            'name'           => $data['name'],
            'slug'           => $data['slug'],
            'description'    => $data['description'] ?? null,
            'price'          => $data['price'],
            'stock_quantity' => $data['stock_quantity'] ?? 0,
            'image_path'     => $data['image_path'] ?? null,
            'brand'          => $data['brand'] ?? null,
            'status'         => $data['status'] ?? 'show'
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function decreaseStock(int $id, int $quantity): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE products 
            SET stock_quantity = stock_quantity - :quantity 
            WHERE id = :id AND stock_quantity >= :quantity
        ");
        $stmt->execute([
            'id' => $id,
            'quantity' => $quantity
        ]);
        
        return $stmt->rowCount() > 0;
    }
}
