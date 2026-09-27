<?php
/**
 * app/Models/PDOFactory.php — Tạo kết nối PDO đến PostgreSQL
 * [C2] Kết nối CSDL an toàn, dùng Prepared Statement chống SQL Injection
 * [C3] Class được autoload qua PSR-4: App\Models\PDOFactory
 *
 * Cách dùng:
 *   $factory = new PDOFactory();
 *   $pdo = $factory->create([
 *       'dbhost' => $_ENV['DB_HOST'],
 *       'dbname' => $_ENV['DB_NAME'],
 *       'dbuser' => $_ENV['DB_USER'],
 *       'dbpass' => $_ENV['DB_PASS'],
 *   ]);
 */

namespace App\Models;

use PDO;
use PDOException;

class PDOFactory
{
    /**
     * Tạo và trả về đối tượng PDO đã kết nối đến PostgreSQL
     *
     * @param  array $config Mảng chứa thông tin kết nối (dbhost, dbname, dbuser, dbpass)
     * @return PDO
     * @throws PDOException Nếu kết nối thất bại
     */
    public function create(array $config): PDO
    {
        // [C4] Destructuring — tách biến từ mảng config
        [
            'dbhost' => $dbhost,
            'dbname' => $dbname,
            'dbuser' => $dbuser,
            'dbpass' => $dbpass
        ] = $config;

        // DSN (Data Source Name) cho PostgreSQL
        $dsn = "pgsql:host={$dbhost};dbname={$dbname};";

        // Tạo kết nối PDO với các tùy chọn an toàn
        $pdo = new PDO($dsn, $dbuser, $dbpass, [
            // Khi có lỗi SQL → ném Exception (dễ debug, dễ bắt lỗi)
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

            // Trả kết quả dạng object thay vì array (truy cập bằng $row->name thay vì $row['name'])
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,

            // Tắt prepared statement giả lập — dùng native prepared statement của PostgreSQL (an toàn hơn)
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        return $pdo;
    }
}
