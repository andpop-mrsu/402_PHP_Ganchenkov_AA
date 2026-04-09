<?php

declare(strict_types=1);

function createGame(string $playerName): int
{
    $connection = getConnection();
    $gameData = generateProgressionData();

    $statement = $connection->prepare(
        'INSERT INTO games (
            player_name,
            progression,
            masked_progression,
            hidden_value,
            created_at
        ) VALUES (
            :player_name,
            :progression,
            :masked_progression,
            :hidden_value,
            :created_at
        )'
    );

    $statement->execute([
        ':player_name' => $playerName,
        ':progression' => $gameData['progression'],
        ':masked_progression' => $gameData['masked_progression'],
        ':hidden_value' => $gameData['hidden_value'],
        ':created_at' => date('Y-m-d H:i:s'),
    ]);

    return (int) $connection->lastInsertId();
}

/**
 * @return array<string, mixed>|null
 */
function findGame(int $gameId): ?array
{
    $connection = getConnection();
    $statement = $connection->prepare('SELECT * FROM games WHERE id = :id');
    $statement->execute([':id' => $gameId]);
    $game = $statement->fetch();

    return $game === false ? null : $game;
}

/**
 * @return array<string, mixed>|null
 */
function finishGame(int $gameId, string $playerAnswer): ?array
{
    $game = findGame($gameId);

    if ($game === null) {
        return null;
    }

    $isCorrect = $playerAnswer === (string) $game['hidden_value'];
    $connection = getConnection();
    $statement = $connection->prepare(
        'UPDATE games
        SET player_answer = :player_answer,
            is_correct = :is_correct,
            finished_at = :finished_at
        WHERE id = :id'
    );

    $statement->execute([
        ':player_answer' => $playerAnswer,
        ':is_correct' => $isCorrect ? 1 : 0,
        ':finished_at' => date('Y-m-d H:i:s'),
        ':id' => $gameId,
    ]);

    return findGame($gameId);
}

/**
 * @return array<int, array<string, mixed>>
 */
function fetchGames(): array
{
    $connection = getConnection();
    $statement = $connection->query('SELECT * FROM games ORDER BY id DESC');

    return $statement->fetchAll();
}

/**
 * @return array<int, array<string, mixed>>
 */
function fetchRecentGames(int $limit = 5): array
{
    $connection = getConnection();
    $statement = $connection->prepare('SELECT * FROM games ORDER BY id DESC LIMIT :limit');
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchAll();
}
