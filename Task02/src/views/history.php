<?php include __DIR__ . '/partials/header.php'; ?>

<section class="card">
    <div class="section-head">
        <h2>История игр</h2>
        <span class="badge"><?= escape((string) count($games)) ?> записей</span>
    </div>

    <?php if ($games === []): ?>
        <p>База данных пока пуста. Начните игру на главной странице.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Игрок</th>
                    <th>Создано</th>
                    <th>Прогрессия</th>
                    <th>Ответ игрока</th>
                    <th>Пропущено</th>
                    <th>Результат</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($games as $game): ?>
                    <tr>
                        <td><?= escape((string) $game['id']) ?></td>
                        <td><?= escape($game['player_name']) ?></td>
                        <td><?= escape($game['created_at']) ?></td>
                        <td><?= escape($game['progression']) ?></td>
                        <td><?= escape($game['player_answer'] ?? '—') ?></td>
                        <td><?= escape((string) $game['hidden_value']) ?></td>
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
