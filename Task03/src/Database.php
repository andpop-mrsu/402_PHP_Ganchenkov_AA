<?php

declare(strict_types=1);

namespace Relflly\Task03;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $databasePath = __DIR__ . '/../db/progression.sqlite';
        $databaseDirectory = dirname($databasePath);

        if (!is_dir($databaseDirectory)) {
            mkdir($databaseDirectory, 0777, true);
        }

        $connection = new PDO('sqlite:' . $databasePath);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $connection->exec(
            'CREATE TABLE IF NOT EXISTS games (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                player_name TEXT NOT NULL,
                progression TEXT NOT NULL,
                masked_progression TEXT NOT NULL,
                hidden_value INTEGER NOT NULL,
                status TEXT NOT NULL,
                created_at TEXT NOT NULL,
                finished_at TEXT DEFAULT NULL
            )'
        );

        $connection->exec(
            'CREATE TABLE IF NOT EXISTS steps (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                game_id INTEGER NOT NULL,
                answer TEXT NOT NULL,
                is_correct INTEGER NOT NULL,
                created_at TEXT NOT NULL,
                FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
            )'
        );

        self::$connection = $connection;

        return self::$connection;
    }
}
