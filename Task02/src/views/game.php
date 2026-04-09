<?php include __DIR__ . '/partials/header.php'; ?>

<section class="card">
    <div class="section-head">
        <h2>Игра #<?= escape((string) $game['id']) ?></h2>
        <span class="badge"><?= escape($game['player_name']) ?></span>
    </div>

    <p><strong>Правило:</strong> найдите пропущенное число в арифметической прогрессии.</p>
    <p class="question"><?= escape($game['masked_progression']) ?></p>

    <?php if ($game['player_answer'] === null): ?>
        <form method="post" action="/game.php?id=<?= escape((string) $game['id']) ?>" class="stack">
            <label for="player_answer">Ваш ответ</label>
            <input id="player_answer" name="player_answer" type="text" maxlength="50" required>
            <button type="submit">Проверить ответ</button>
        </form>
    <?php else: ?>
        <div class="result <?= (int) $game['is_correct'] === 1 ? 'success' : 'error' ?>">
            <?php if ((int) $game['is_correct'] === 1): ?>
                <p>Ответ верный. Поздравляем, <?= escape($game['player_name']) ?>!</p>
            <?php else: ?>
                <p>Ответ "<?= escape($game['player_answer']) ?>" неверный. Правильный ответ: "<?= escape((string) $game['hidden_value']) ?>".</p>
            <?php endif; ?>
            <p>Полная прогрессия: <?= escape($game['progression']) ?></p>
        </div>

        <div class="actions">
            <a class="button-link" href="/">Новая игра</a>
            <a class="button-link secondary" href="/history.php">История игр</a>
        </div>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
