<?php

declare(strict_types=1);

namespace Relflly\Task03;

use PDO;

final class GameRepository
{
    public function __construct(private readonly PDO $connection)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function allGames(): array
    {
        $statement = $this->connection->query(
            'SELECT
                g.id,
                g.player_name,
                g.progression,
                g.masked_progression,
                g.hidden_value,
                g.status,
                g.created_at,
                g.finished_at,
                COUNT(s.id) AS steps_count
            FROM games g
            LEFT JOIN steps s ON s.game_id = g.id
            GROUP BY g.id
            ORDER BY g.id DESC'
        );

        $games = $statement->fetchAll();

        return array_map(
            fn (array $game): array => $this->mapGameSummary($game),
            $games
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findGame(int $id): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT
                id,
                player_name,
                progression,
                masked_progression,
                hidden_value,
                status,
                created_at,
                finished_at
            FROM games
            WHERE id = :id'
        );
        $statement->execute([':id' => $id]);
        $game = $statement->fetch();

        if ($game === false) {
            return null;
        }

        $stepsStatement = $this->connection->prepare(
            'SELECT id, answer, is_correct, created_at
            FROM steps
            WHERE game_id = :game_id
            ORDER BY id ASC'
        );
        $stepsStatement->execute([':game_id' => $id]);
        $steps = $stepsStatement->fetchAll();

        return [
            'id' => (int) $game['id'],
            'playerName' => $game['player_name'],
            'progression' => $game['progression'],
            'maskedProgression' => $game['masked_progression'],
            'hiddenValue' => (int) $game['hidden_value'],
            'status' => $game['status'],
            'createdAt' => $game['created_at'],
            'finishedAt' => $game['finished_at'],
            'steps' => array_map(
                static fn (array $step): array => [
                    'id' => (int) $step['id'],
                    'answer' => $step['answer'],
                    'isCorrect' => (bool) $step['is_correct'],
                    'createdAt' => $step['created_at'],
                ],
                $steps
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function createGame(string $playerName): array
    {
        $game = ProgressionGenerator::generate();

        $statement = $this->connection->prepare(
            'INSERT INTO games (
                player_name,
                progression,
                masked_progression,
                hidden_value,
                status,
                created_at
            ) VALUES (
                :player_name,
                :progression,
                :masked_progression,
                :hidden_value,
                :status,
                :created_at
            )'
        );

        $statement->execute([
            ':player_name' => $playerName,
            ':progression' => $game['progression'],
            ':masked_progression' => $game['maskedProgression'],
            ':hidden_value' => $game['hiddenValue'],
            ':status' => 'active',
            ':created_at' => date('Y-m-d H:i:s'),
        ]);

        $id = (int) $this->connection->lastInsertId();

        return $this->findGame($id);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function addStep(int $gameId, string $answer): ?array
    {
        $game = $this->findGame($gameId);

        if ($game === null) {
            return null;
        }

        if ($game['status'] !== 'active') {
            return [
                'error' => 'Game already finished.',
                'game' => $game,
            ];
        }

        $isCorrect = $answer === (string) $game['hiddenValue'];

        $stepStatement = $this->connection->prepare(
            'INSERT INTO steps (game_id, answer, is_correct, created_at)
            VALUES (:game_id, :answer, :is_correct, :created_at)'
        );
        $stepStatement->execute([
            ':game_id' => $gameId,
            ':answer' => $answer,
            ':is_correct' => $isCorrect ? 1 : 0,
            ':created_at' => date('Y-m-d H:i:s'),
        ]);

        $status = $isCorrect ? 'won' : 'lost';
        $gameStatement = $this->connection->prepare(
            'UPDATE games
            SET status = :status, finished_at = :finished_at
            WHERE id = :id'
        );
        $gameStatement->execute([
            ':status' => $status,
            ':finished_at' => date('Y-m-d H:i:s'),
            ':id' => $gameId,
        ]);

        $updatedGame = $this->findGame($gameId);

        return [
            'message' => $isCorrect ? 'Correct answer.' : 'Wrong answer.',
            'game' => $updatedGame,
        ];
    }

    /**
     * @param array<string, mixed> $game
     * @return array<string, mixed>
     */
    private function mapGameSummary(array $game): array
    {
        return [
            'id' => (int) $game['id'],
            'playerName' => $game['player_name'],
            'progression' => $game['progression'],
            'maskedProgression' => $game['masked_progression'],
            'hiddenValue' => (int) $game['hidden_value'],
            'status' => $game['status'],
            'createdAt' => $game['created_at'],
            'finishedAt' => $game['finished_at'],
            'stepsCount' => (int) $game['steps_count'],
        ];
    }
}
