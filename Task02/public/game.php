<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['id'])) {
    $playerName = trim((string) ($_POST['player_name'] ?? ''));

    if ($playerName === '') {
        setFlash('error', 'Введите имя игрока перед началом новой игры.');
        redirect('/');
    }

    $gameId = createGame($playerName);
    redirect('/game.php?id=' . $gameId);
}

$gameId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($gameId === false || $gameId === null) {
    setFlash('error', 'Игра не найдена.');
    redirect('/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerAnswer = trim((string) ($_POST['player_answer'] ?? ''));

    if ($playerAnswer === '') {
        setFlash('error', 'Введите ответ на прогрессию.');
        redirect('/game.php?id=' . $gameId);
    }

    finishGame($gameId, $playerAnswer);
    redirect('/game.php?id=' . $gameId);
}

$game = findGame($gameId);

if ($game === null) {
    setFlash('error', 'Игра не найдена.');
    redirect('/');
}

$pageTitle = 'Progression: игра';
$flash = getFlash();

include __DIR__ . '/../src/views/game.php';
