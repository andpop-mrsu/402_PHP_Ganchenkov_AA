<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($pageTitle ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="page">
    <header class="hero">
        <div>
            <p class="eyebrow">Task02</p>
            <h1><?= escape($pageTitle ?? APP_NAME) ?></h1>
            <p class="subtitle">Веб-версия игры "Арифметическая прогрессия" с хранением результатов в SQLite.</p>
        </div>
        <nav class="nav">
            <a href="/">Главная</a>
            <a href="/history.php">История</a>
        </nav>
    </header>

    <?php if (isset($flash) && $flash !== null): ?>
        <section class="flash flash-<?= escape($flash['type']) ?>">
            <?= escape($flash['message']) ?>
        </section>
    <?php endif; ?>

    <main class="content">
