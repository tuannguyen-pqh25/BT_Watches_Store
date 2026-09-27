<?php
/**
 * app/Models/Order.php — Model đơn hàng
 * [C2] Transaction: tạo đơn + order_items + trừ tồn kho trong 1 giao dịch
 * [C4] Prepared Statement chống SQL Injection
 */

namespace App\Models;

use PDO;
use Exception;

class Order
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * [C2] Tạo đơn hàng mới (Transaction: đơn + order_items + trừ tồn kho)
     * Nếu 1 bước lỗi → rollback toàn bộ
     */
    public function create(array $orderData, array $cartItems): int
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Thêm vào bảng orders
            $sqlOrder = "INSERT INTO orders (user_id, order_code, total_amount, shipping_name, shipping_address, shipping_phone, shipping_email, payment_method, note) 
                         VALUES (:user_id, :order_code, :total_amount, :shipping_name, :shipping_address, :shipping_phone, :shipping_email, :payment_method, :note) 
                         RETURNING id";
            $stmtOrder = $this->pdo->prepare($sqlOrder);
            $stmtOrder->execute([
                'user_id'          => $orderData['user_id'],
                'order_code'       => $orderData['order_code'],
                'total_amount'     => $orderData['total_amount'],
                'shipping_name'    => $orderData['shipping_name'],
                'shipping_address' => $orderData['shipping_address'],
                'shipping_phone'   => $orderData['shipping_phone'],
                'shipping_email'   => $orderData['shipping_email'] ?? null,
                'payment_method'   => $orderData['payment_method'] ?? 'COD',
                'note'             => $orderData['note'] ?? null
            ]);
            
            $orderId = $stmtOrder->fetchColumn();

            // 2. Thêm từng order_item + trừ tồn kho
            $stmtItem  = $this->pdo->prepare(
                "INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (:order_id, :product_id, :quantity, :unit_price)"
            );
            $stmtStock = $this->pdo->prepare(
                "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :id AND stock_quantity >= :quantity"
            );

            foreach ($cartItems as $item) {
                $stmtItem->execute([
                    'order_id'   => $orderId,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'unit_price' => $item['price']
                ]);

                // Trừ tồn kho
                $stmtStock->execute([
                    'id'       => $item['id'],
                    'quantity' => $item['qty']
                ]);

                // Kiểm tra tồn kho đủ không (rowCount = 0 → không đủ hàng)
                if ($stmtStock->rowCount() === 0) {
                    throw new Exception("Sản phẩm '{$item['name']}' không đủ số lượng trong kho.");
                }
            }

            $this->pdo->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /** Lấy danh sách đơn hàng của 1 user */
    public function getByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy đơn hàng theo ID
     * [C4] Chống IDOR: truyền $userId → chỉ trả đơn thuộc user đó
     */
    public function getById(int $id, ?int $userId = null): ?object
    {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $params = ['id' => $id];

        if ($userId !== null) {
            $sql .= ' AND user_id = :user_id';
            $params['user_id'] = $userId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    /** Lấy danh sách sản phẩm trong đơn hàng (JOIN products) */
    public function getItems(int $orderId): array
    {
        $sql = "SELECT oi.*, p.name as product_name, p.image_path, p.slug 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    /** Cập nhật trạng thái đơn hàng */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            'id'     => $id,
            'status' => $status
        ]);
    }

    /** Đếm tổng số đơn hàng */
    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM orders");
        return (int)$stmt->fetchColumn();
    }

    /** Tổng doanh thu từ đơn hàng đã hoàn thành */
    public function getTotalRevenue(): float
    {
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status IN ('delivered', 'Hoàn thành')");
        return (float)$stmt->fetchColumn();
    }
}
