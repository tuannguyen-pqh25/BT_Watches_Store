<?php
/**
 * app/Models/User.php
 * [C2] Xử lý nghiệp vụ người dùng
 * [C4] Dùng Prepared Statement chống SQL Injection
 */

namespace App\Models;

use PDO;

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?object
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /** Lấy tất cả users (dùng cho Admin) */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, email, full_name, phone, role, avatar_path, created_at, updated_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /** Đếm tổng số users */
    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return (int) $stmt->fetchColumn();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO users (email, password, full_name, phone, role) 
                VALUES (:email, :password, :full_name, :phone, :role)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'email'     => $data['email'],
            'password'  => password_hash($data['password'], PASSWORD_DEFAULT), #Giải thuật băm Bcrypt
            'full_name' => $data['full_name'],
            'phone'     => $data['phone'] ?? null,
            'role'      => $data['role'] ?? 'customer'
        ]);
    }

    public function updateAvatar(int $id, string $avatarPath): bool
    {
        $sql = "UPDATE users SET avatar_path = :avatar_path, updated_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'          => $id,
            'avatar_path' => $avatarPath
        ]);
    }

    /** Cập nhật hồ sơ người dùng (tên, điện thoại) */
    public function updateProfile(int $id, array $data): bool
    {
        $sql = "UPDATE users SET full_name = :full_name, phone = :phone, updated_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'        => $id,
            'full_name' => $data['full_name'],
            'phone'     => $data['phone'] ?? null,
        ]);
    }

    /** Cập nhật mật khẩu */
    public function updatePassword(int $id, string $newPassword): bool
    {
        $sql = "UPDATE users SET password = :password, updated_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'       => $id,
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);
    }
}
