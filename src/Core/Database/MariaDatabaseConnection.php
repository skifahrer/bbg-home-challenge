<?php

declare(strict_types=1);

namespace App\Core\Database;

use InvalidArgumentException;
use PDO;

class MariaDatabaseConnection implements DatabaseConnectionInterface
{
    private PDO $manager;

    public function __construct()
    {
        $this->manager = $this->createPdoFromUrl($_ENV['DB_DSN'] ?? '');
    }

    private function createPdoFromUrl(string $databaseUrl): PDO
    {
        $parts = parse_url($databaseUrl);

        parse_str($parts['query'] ?? '', $query);

        if ($parts === false || !isset($parts['scheme'], $parts['host'], $parts['user'], $parts['pass'], $parts['path'])) {
            throw new InvalidArgumentException("Invalid database URL.");
        }

        $scheme = $parts['scheme'];
        $host = $parts['host'];
        $port = $parts['port'] ?? 3306;
        $user = $parts['user'];
        $pass = $parts['pass'];
        $dbname = ltrim($parts['path'], '/'); // remove leading slash

        // Parse additional query parameters
        parse_str($parts['query'] ?? '', $queryParams);

        $charset = $queryParams['charset'] ?? 'utf8mb4';

        // Build DSN
        $dsn = sprintf('%s:host=%s;port=%d;dbname=%s;charset=%s', $scheme, $host, $port, $dbname, $charset);

        // Create PDO instance
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function getManager(): PDO
    {
        return $this->manager;
    }
}