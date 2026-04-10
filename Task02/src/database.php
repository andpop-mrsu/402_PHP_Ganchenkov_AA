<?php

declare(strict_types=1);

function getDatabasePath(): string
{
    return __DIR__ . '/../db/progression.sqlite';
}

function getConnection(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $databasePath = getDatabasePath();
    $databaseDirectory = dirname($databasePath);

    if (!is_dir($databaseDirectory)) {
        mkdir($databaseDirectory, 0777, true);
    }

    $connection = new PDO('sqlite:' . $databasePath);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    initializeDatabase($connection);

    return $connection;
}

function initializeDatabase(PDO $connection): void
{
    $connection->exec(
        'CREATE TABLE IF NOT EXISTS games (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            player_name TEXT NOT NULL,
            progression TEXT NOT NULL,
            masked_progression TEXT NOT NULL,
            hidden_value INTEGER NOT NULL,
            player_answer TEXT DEFAULT NULL,
            is_correct INTEGER DEFAULT NULL,
            created_at TEXT NOT NULL,
            finished_at TEXT DEFAULT NULL
        )'
    );
}
