<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Database
{
    private PDO $connection;

    public function __construct(array $config)
    {
        $host = $config['host'] ?? '127.0.0.1';
        $db = $config['database'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';
        $user = $config['username'] ?? '';
        $pass = $config['password'] ?? '';

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

        $this->connection = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
