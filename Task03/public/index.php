<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Relflly\Task03\Database;
use Relflly\Task03\GameRepository;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

$repository = new GameRepository(Database::connection());

/**
 * @param array<string, mixed> $payload
 */
$json = static function (Response $response, array $payload, int $status = 200): Response {
    $response->getBody()->write(
        json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        )
    );

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status);
};

$app->get('/', function (Request $request, Response $response) {
    $html = file_get_contents(__DIR__ . '/index.html');
    $response->getBody()->write($html === false ? '' : $html);

    return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
});

$app->get('/index.html', function (Request $request, Response $response) {
    $html = file_get_contents(__DIR__ . '/index.html');
    $response->getBody()->write($html === false ? '' : $html);

    return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
});

$app->get('/games', function (Request $request, Response $response) use ($repository, $json) {
    return $json($response, ['games' => $repository->allGames()]);
});

$app->get('/games/{id}', function (Request $request, Response $response, array $args) use ($repository, $json) {
    $game = $repository->findGame((int) $args['id']);

    if ($game === null) {
        return $json($response, ['error' => 'Game not found.'], 404);
    }

    return $json($response, ['game' => $game]);
});

$app->post('/games', function (Request $request, Response $response) use ($repository, $json) {
    /** @var array<string, mixed>|null $data */
    $data = $request->getParsedBody();
    $playerName = trim((string) ($data['playerName'] ?? $data['player_name'] ?? ''));

    if ($playerName === '') {
        return $json($response, ['error' => 'playerName is required.'], 400);
    }

    $game = $repository->createGame($playerName);

    return $json(
        $response,
        [
            'id' => $game['id'],
            'game' => $game,
        ],
        201
    );
});

$app->post('/step/{id}', function (Request $request, Response $response, array $args) use ($repository, $json) {
    /** @var array<string, mixed>|null $data */
    $data = $request->getParsedBody();
    $answer = trim((string) ($data['answer'] ?? ''));

    if ($answer === '') {
        return $json($response, ['error' => 'answer is required.'], 400);
    }

    $result = $repository->addStep((int) $args['id'], $answer);

    if ($result === null) {
        return $json($response, ['error' => 'Game not found.'], 404);
    }

    if (isset($result['error'])) {
        return $json($response, $result, 409);
    }

    return $json($response, $result, 201);
});

$app->run();
