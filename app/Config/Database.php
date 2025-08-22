<?php

namespace App\Config;

use PDO;
use PDOException;

final class Database
{
    public static function getConnection(): PDO
    {
        try {
            return new PDO(
                    "mysql:host=127.0.0.1;dbname=tarefas_db;charset=utf8mb4",
                    "root",
                    "root",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
        } catch (PDOException $e) {
            http_response_code(500);
            exit("Erro de conexão: " . $e->getMessage());
        }
    }
}
