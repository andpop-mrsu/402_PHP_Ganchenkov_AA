<?php include __DIR__ . '/partials/header.php'; ?>

<section class="card">
    <h2>Новая игра</h2>
    <p>Введите имя игрока, после чего приложение создаст новую арифметическую прогрессию и сохранит игру в базу данных.</p>

    <form method="post" action="/game.php" class="stack">
        <label for="player_name">Имя игрока</label>
        <input id="player_name" name="player_name" type="text" maxlength="100" required>
        <button type="submit">Начать игру</button>
    </form>
</section>

<section class="card">
    <div class="section-head">
        <h2>Последние игры</h2>
        <a class="text-link" href="/history.php">Смотреть всю историю</a>
    </div>

    <?php if ($recentGames === []): ?>
        <p>Пока нет сохранённых игр. Начните первую игру, и она появится в истории.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Игрок</th>
                    <th>Дата</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentGames as $game): ?>
                    <tr>
                        <td><?= escape((string) $game['id']) ?></td>
                        <td><?= escape($game['player_name']) ?></td>
                        <td><?= escape($game['created_at']) ?></td>
                        <td>
                            <?php if ($game['player_answer'] === null): ?>
                                В процессе
                            <?php elseif ((int) $game['is_correct'] === 1): ?>
                                Угадано
                            <?php else: ?>
                                Ошибка
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
