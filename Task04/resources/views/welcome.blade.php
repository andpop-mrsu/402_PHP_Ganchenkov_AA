<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task04 | Арифметическая прогрессия</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f4efe4;
            --paper: rgba(255, 251, 244, 0.92);
            --paper-strong: #fffdf7;
            --ink: #1f1a16;
            --muted: #6f665e;
            --accent: #b85042;
            --accent-dark: #7b2d26;
            --secondary: #2f6b5b;
            --line: rgba(31, 26, 22, 0.12);
            --shadow: 0 20px 50px rgba(64, 41, 24, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(184, 80, 66, 0.18), transparent 28%),
                radial-gradient(circle at top right, rgba(47, 107, 91, 0.16), transparent 24%),
                linear-gradient(135deg, #f7f0e2 0%, #efe6d5 45%, #eadfca 100%);
        }

        .page {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0 40px;
        }

        .hero {
            padding: 28px;
            border: 1px solid rgba(255, 255, 255, 0.65);
            border-radius: 28px;
            background: linear-gradient(145deg, rgba(255, 252, 246, 0.96), rgba(247, 241, 232, 0.9));
            box-shadow: var(--shadow);
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 14px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(184, 80, 66, 0.1);
            color: var(--accent-dark);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0;
        }

        .hero h1 {
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(32px, 5vw, 54px);
            line-height: 1.02;
            max-width: 10ch;
        }

        .hero p {
            max-width: 760px;
            margin-top: 16px;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.6;
        }

        .api-note {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
        }

        .api-note span {
            padding: 9px 12px;
            border-radius: 12px;
            background: rgba(47, 107, 91, 0.08);
            border: 1px solid rgba(47, 107, 91, 0.14);
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 20px;
            margin-top: 22px;
        }

        .stack {
            display: grid;
            gap: 20px;
        }

        .card {
            padding: 22px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: var(--paper);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }

        .card h2,
        .card h3 {
            font-family: Georgia, "Times New Roman", serif;
            margin-bottom: 10px;
        }

        .subtle {
            color: var(--muted);
            line-height: 1.5;
        }

        .form-grid {
            display: grid;
            gap: 14px;
            margin-top: 18px;
        }

        label {
            display: grid;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 14px;
            font: inherit;
            color: var(--ink);
            background: rgba(255, 255, 255, 0.9);
        }

        input:focus {
            outline: 2px solid rgba(184, 80, 66, 0.25);
            border-color: rgba(184, 80, 66, 0.45);
        }

        button {
            width: fit-content;
            padding: 13px 18px;
            border: 0;
            border-radius: 14px;
            font: inherit;
            font-weight: 700;
            color: #fff8f3;
            background: linear-gradient(135deg, var(--accent) 0%, #cb6b5f 100%);
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 12px 24px rgba(184, 80, 66, 0.2);
        }

        button:hover {
            transform: translateY(-1px);
        }

        button.secondary {
            background: linear-gradient(135deg, var(--secondary) 0%, #3d8a73 100%);
            box-shadow: 0 12px 24px rgba(47, 107, 91, 0.18);
        }

        button:disabled {
            opacity: 0.6;
            cursor: wait;
            transform: none;
        }

        .progression {
            margin-top: 18px;
            padding: 18px;
            border-radius: 18px;
            background: var(--paper-strong);
            border: 1px solid rgba(31, 26, 22, 0.08);
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(24px, 4vw, 34px);
            letter-spacing: 0.06em;
            text-align: center;
            word-break: break-word;
        }

        .status {
            min-height: 24px;
            margin-top: 14px;
            font-size: 14px;
            color: var(--muted);
        }

        .status.error {
            color: var(--accent-dark);
        }

        .status.success {
            color: var(--secondary);
        }

        .result-box {
            display: none;
            margin-top: 18px;
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(47, 107, 91, 0.08);
            border: 1px solid rgba(47, 107, 91, 0.14);
        }

        .result-box.bad {
            background: rgba(184, 80, 66, 0.08);
            border-color: rgba(184, 80, 66, 0.15);
        }

        .result-meta {
            display: grid;
            gap: 8px;
            margin-top: 12px;
            color: var(--muted);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            font-size: 14px;
        }

        th,
        td {
            padding: 12px 10px;
            border-bottom: 1px solid rgba(31, 26, 22, 0.08);
            text-align: left;
            vertical-align: top;
        }

        th {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        tbody tr {
            cursor: pointer;
            transition: background 0.15s ease;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(31, 26, 22, 0.07);
            font-size: 12px;
            font-weight: 700;
        }

        .pill.good {
            background: rgba(47, 107, 91, 0.15);
            color: var(--secondary);
        }

        .pill.bad {
            background: rgba(184, 80, 66, 0.13);
            color: var(--accent-dark);
        }

        .detail-grid {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }

        .detail-item {
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(31, 26, 22, 0.08);
        }

        .detail-item strong {
            display: block;
            margin-bottom: 6px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
        }

        .empty {
            padding: 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.56);
            color: var(--muted);
        }

        @media (max-width: 920px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<main class="page">
    <section class="hero">
        <span class="eyebrow">Task04 • Laravel</span>
        <h1>Арифметическая прогрессия</h1>
        <p>
            Одностраничное приложение для игры из предыдущей лабораторной работы.
            Сервер генерирует прогрессию, сохраняет партии в SQLite и отдаёт историю через REST API.
        </p>
        <div class="api-note">
            <span><strong>GET</strong> /api/games</span>
            <span><strong>GET</strong> /api/games/{id}</span>
            <span><strong>POST</strong> /api/games</span>
            <span><strong>POST</strong> /api/step/{id}</span>
        </div>
    </section>

    <section class="grid">
        <div class="stack">
            <article class="card">
                <h2>Новая игра</h2>
                <p class="subtle">Введите имя игрока, чтобы получить прогрессию из 10 чисел с одним пропуском.</p>
                <form id="start-form" class="form-grid">
                    <label>
                        Имя игрока
                        <input id="player-name" name="player_name" type="text" maxlength="100" placeholder="Например, Андрей" required>
                    </label>
                    <button id="start-button" type="submit">Начать игру</button>
                </form>
                <div id="start-status" class="status"></div>
            </article>

            <article class="card">
                <h2>Текущая партия</h2>
                <p class="subtle">Найдите пропущенное число и отправьте ответ на сервер.</p>
                <div id="current-progression" class="progression">Партия ещё не начата</div>
                <form id="answer-form" class="form-grid">
                    <label>
                        Ваш ответ
                        <input id="answer-input" name="answer" type="number" placeholder="Введите число" disabled required>
                    </label>
                    <button id="answer-button" class="secondary" type="submit" disabled>Проверить ответ</button>
                </form>
                <div id="answer-status" class="status"></div>
                <div id="result-box" class="result-box">
                    <h3 id="result-title">Результат</h3>
                    <p id="result-message" class="subtle"></p>
                    <div class="result-meta">
                        <span id="result-answer"></span>
                        <span id="result-hidden"></span>
                        <span id="result-full"></span>
                    </div>
                </div>
            </article>
        </div>

        <div class="stack">
            <article class="card">
                <h2>История игр</h2>
                <p class="subtle">Кликните по записи, чтобы посмотреть данные конкретной партии.</p>
                <div id="history-empty" class="empty">Пока нет сохранённых игр.</div>
                <table id="history-table" hidden>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Игрок</th>
                        <th>Статус</th>
                        <th>Дата</th>
                    </tr>
                    </thead>
                    <tbody id="history-body"></tbody>
                </table>
            </article>

            <article class="card">
                <h2>Детали партии</h2>
                <div id="details-empty" class="empty">Выберите партию из истории или завершите текущую игру.</div>
                <div id="details" class="detail-grid" hidden>
                    <div class="detail-item">
                        <strong>Игрок</strong>
                        <span id="detail-player"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Показанная прогрессия</strong>
                        <span id="detail-masked"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Полная прогрессия</strong>
                        <span id="detail-full"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Пропущенное число</strong>
                        <span id="detail-hidden"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Ответ игрока</strong>
                        <span id="detail-answer"></span>
                    </div>
                    <div class="detail-item">
                        <strong>Результат</strong>
                        <span id="detail-result"></span>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>

<script>
    const state = {
        currentGame: null,
    };

    const elements = {
        startForm: document.querySelector('#start-form'),
        startButton: document.querySelector('#start-button'),
        playerName: document.querySelector('#player-name'),
        startStatus: document.querySelector('#start-status'),
        currentProgression: document.querySelector('#current-progression'),
        answerForm: document.querySelector('#answer-form'),
        answerButton: document.querySelector('#answer-button'),
        answerInput: document.querySelector('#answer-input'),
        answerStatus: document.querySelector('#answer-status'),
        resultBox: document.querySelector('#result-box'),
        resultTitle: document.querySelector('#result-title'),
        resultMessage: document.querySelector('#result-message'),
        resultAnswer: document.querySelector('#result-answer'),
        resultHidden: document.querySelector('#result-hidden'),
        resultFull: document.querySelector('#result-full'),
        historyEmpty: document.querySelector('#history-empty'),
        historyTable: document.querySelector('#history-table'),
        historyBody: document.querySelector('#history-body'),
        detailsEmpty: document.querySelector('#details-empty'),
        details: document.querySelector('#details'),
        detailPlayer: document.querySelector('#detail-player'),
        detailMasked: document.querySelector('#detail-masked'),
        detailFull: document.querySelector('#detail-full'),
        detailHidden: document.querySelector('#detail-hidden'),
        detailAnswer: document.querySelector('#detail-answer'),
        detailResult: document.querySelector('#detail-result'),
    };

    const jsonHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    const setStatus = (element, message, type = '') => {
        element.textContent = message;
        element.className = `status ${type}`.trim();
    };

    const formatDate = (value) => {
        if (!value) {
            return 'Не указано';
        }

        return new Intl.DateTimeFormat('ru-RU', {
            dateStyle: 'short',
            timeStyle: 'medium',
        }).format(new Date(value));
    };

    const createStatusPill = (game) => {
        if (game.status !== 'completed') {
            return '<span class="pill">В процессе</span>';
        }

        return game.is_correct
            ? '<span class="pill good">Верно</span>'
            : '<span class="pill bad">Ошибка</span>';
    };

    const renderCurrentGame = (game) => {
        state.currentGame = game;
        elements.currentProgression.textContent = game.masked_progression;
        elements.answerInput.disabled = false;
        elements.answerButton.disabled = false;
        elements.answerInput.value = '';
        elements.answerInput.focus();
        setStatus(elements.answerStatus, 'Партия создана. Введите пропущенное число.', 'success');
        elements.resultBox.style.display = 'none';
    };

    const renderHistory = (games) => {
        elements.historyBody.innerHTML = '';

        if (games.length === 0) {
            elements.historyEmpty.hidden = false;
            elements.historyTable.hidden = true;
            return;
        }

        elements.historyEmpty.hidden = true;
        elements.historyTable.hidden = false;

        games.forEach((game) => {
            const row = document.createElement('tr');
            row.dataset.id = game.id;
            row.innerHTML = `
                <td>#${game.id}</td>
                <td>${game.player_name}</td>
                <td>${createStatusPill(game)}</td>
                <td>${formatDate(game.played_at)}</td>
            `;
            row.addEventListener('click', () => loadGame(game.id));
            elements.historyBody.appendChild(row);
        });
    };

    const renderDetails = (game) => {
        elements.detailsEmpty.hidden = true;
        elements.details.hidden = false;
        elements.detailPlayer.textContent = game.player_name;
        elements.detailMasked.textContent = game.masked_progression;
        elements.detailAnswer.textContent = game.player_answer ?? 'Ответ ещё не отправлен';

        if (game.status !== 'completed') {
            elements.detailFull.textContent = 'Скрыто до завершения партии';
            elements.detailHidden.textContent = 'Скрыто до завершения партии';
            elements.detailResult.textContent = 'Партия ещё не завершена';
            return;
        }

        elements.detailFull.textContent = game.full_progression;
        elements.detailHidden.textContent = game.hidden_value;
        elements.detailResult.textContent = game.is_correct ? 'Ответ верный' : 'Ответ неверный';
    };

    const showResult = (payload) => {
        const { result, message } = payload;
        elements.resultBox.style.display = 'block';
        elements.resultBox.classList.toggle('bad', !result.is_correct);
        elements.resultTitle.textContent = result.is_correct ? 'Ответ верный' : 'Ответ неверный';
        elements.resultMessage.textContent = message;
        elements.resultAnswer.textContent = `Ответ игрока: ${result.player_answer}`;
        elements.resultHidden.textContent = `Правильное число: ${result.hidden_value}`;
        elements.resultFull.textContent = `Полная прогрессия: ${result.full_progression}`;
    };

    const handleApiError = async (response) => {
        const payload = await response.json().catch(() => ({}));

        if (payload.errors) {
            const firstError = Object.values(payload.errors).flat()[0];
            throw new Error(firstError);
        }

        throw new Error(payload.message || 'Не удалось выполнить запрос.');
    };

    const request = async (url, options = {}) => {
        const response = await fetch(url, options);

        if (!response.ok) {
            await handleApiError(response);
        }

        return response.json();
    };

    const refreshHistory = async () => {
        const games = await request('/api/games');
        renderHistory(games);
    };

    const loadGame = async (id) => {
        const game = await request(`/api/games/${id}`);
        renderDetails(game);
    };

    elements.startForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        elements.startButton.disabled = true;
        setStatus(elements.startStatus, 'Создаю новую партию...');

        try {
            const game = await request('/api/games', {
                method: 'POST',
                headers: jsonHeaders,
                body: JSON.stringify({
                    player_name: elements.playerName.value.trim(),
                }),
            });

            renderCurrentGame(game);
            setStatus(elements.startStatus, `Новая игра создана для ${game.player_name}.`, 'success');
            await refreshHistory();
        } catch (error) {
            setStatus(elements.startStatus, error.message, 'error');
        } finally {
            elements.startButton.disabled = false;
        }
    });

    elements.answerForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!state.currentGame) {
            setStatus(elements.answerStatus, 'Сначала начните игру.', 'error');
            return;
        }

        elements.answerButton.disabled = true;
        setStatus(elements.answerStatus, 'Проверяю ответ...');

        try {
            const payload = await request(`/api/step/${state.currentGame.id}`, {
                method: 'POST',
                headers: jsonHeaders,
                body: JSON.stringify({
                    answer: elements.answerInput.value,
                }),
            });

            state.currentGame = payload.game;
            showResult(payload);
            renderDetails(payload.game);
            setStatus(elements.answerStatus, 'Результат сохранён в базе данных.', 'success');
            elements.answerInput.disabled = true;
            elements.answerButton.disabled = true;
            await refreshHistory();
        } catch (error) {
            setStatus(elements.answerStatus, error.message, 'error');
            elements.answerButton.disabled = false;
        }
    });

    refreshHistory().catch((error) => {
        setStatus(elements.startStatus, error.message, 'error');
    });
</script>
</body>
</html>
