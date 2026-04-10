<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

$pageTitle = 'Progression: главная';
$flash = getFlash();
$recentGames = fetchRecentGames();

include __DIR__ . '/../src/views/index.php';
