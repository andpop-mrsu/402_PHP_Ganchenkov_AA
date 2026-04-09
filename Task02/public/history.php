<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

$pageTitle = 'Progression: история';
$flash = getFlash();
$games = fetchGames();

include __DIR__ . '/../src/views/history.php';
