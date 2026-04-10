const state = {
    games: [],
    currentGame: null,
};

const elements = {
    flash: document.getElementById('flash'),
    gamesCount: document.getElementById('games-count'),
    gamesTableBody: document.getElementById('games-table-body'),
    newGameForm: document.getElementById('new-game-form'),
    playerName: document.getElementById('player-name'),
    currentGame: document.getElementById('current-game'),
    currentStatus: document.getElementById('current-status'),
    reloadGames: document.getElementById('reload-games'),
};

const showFlash = (message, type = 'info') => {
    elements.flash.textContent = message;
    elements.flash.className = `flash ${type}`;

    window.clearTimeout(showFlash.timeoutId);
    showFlash.timeoutId = window.setTimeout(() => {
        elements.flash.className = 'flash hidden';
        elements.flash.textContent = '';
    }, 3500);
};

const request = async (url, options = {}) => {
    const response = await fetch(url, {
        headers: {
            'Content-Type': 'application/json',
            ...(options.headers || {}),
        },
        ...options,
    });

    const payload = await response.json();

    if (!response.ok) {
        throw new Error(payload.error || 'Request failed');
    }

    return payload;
};

const renderGames = () => {
    elements.gamesCount.textContent = `${state.games.length} игр`;

    if (state.games.length === 0) {
        elements.gamesTableBody.innerHTML = '<tr><td colspan="6">Пока нет сохранённых игр.</td></tr>';
        return;
    }

    elements.gamesTableBody.innerHTML = state.games.map((game) => `
        <tr>
            <td>${game.id}</td>
            <td>${game.playerName}</td>
            <td>${game.createdAt}</td>
            <td>${translateStatus(game.status)}</td>
            <td>${game.stepsCount}</td>
            <td><button type="button" class="table-button" data-game-id="${game.id}">Открыть</button></td>
        </tr>
    `).join('');
};

const translateStatus = (status) => {
    switch (status) {
        case 'won':
            return 'Угадано';
        case 'lost':
            return 'Ошибка';
        default:
            return 'В процессе';
    }
};

const renderCurrentGame = () => {
    const game = state.currentGame;

    if (!game) {
        elements.currentStatus.textContent = 'Не выбрана';
        elements.currentGame.innerHTML = '<p>Создайте новую игру или выберите запись из истории.</p>';
        return;
    }

    elements.currentStatus.textContent = translateStatus(game.status);

    const steps = game.steps.map((step) => `
        <li>
            Ответ: <strong>${step.answer}</strong>,
            результат: <strong>${step.isCorrect ? 'верно' : 'ошибка'}</strong>,
            время: ${step.createdAt}
        </li>
    `).join('');

    const answerForm = game.status === 'active'
        ? `
            <form id="answer-form" class="stack">
                <label for="player-answer">Ваш ответ</label>
                <input id="player-answer" name="answer" type="text" maxlength="50" required>
                <button type="submit">Отправить ход</button>
            </form>
        `
        : `
            <div class="result ${game.status === 'won' ? 'success' : 'error'}">
                ${game.status === 'won'
                    ? 'Игра завершена победой.'
                    : `Игра завершена. Пропущенное число: ${game.hiddenValue}.`}
            </div>
        `;

    elements.currentGame.innerHTML = `
        <p><strong>Игрок:</strong> ${game.playerName}</p>
        <p><strong>Вопрос:</strong></p>
        <div class="question">${game.maskedProgression}</div>
        ${answerForm}
        <div>
            <p><strong>Полная прогрессия:</strong> ${game.progression}</p>
            <p><strong>Пропущенное число:</strong> ${game.hiddenValue}</p>
        </div>
        <div>
            <h3>Ходы игры</h3>
            ${steps ? `<ul class="steps">${steps}</ul>` : '<p>Ходов пока нет.</p>'}
        </div>
    `;

    const answerFormElement = document.getElementById('answer-form');

    if (answerFormElement) {
        answerFormElement.addEventListener('submit', submitStep);
    }
};

const loadGames = async () => {
    const payload = await request('/games');
    state.games = payload.games;
    renderGames();
};

const loadGame = async (id) => {
    const payload = await request(`/games/${id}`);
    state.currentGame = payload.game;
    renderCurrentGame();
};

const submitNewGame = async (event) => {
    event.preventDefault();

    try {
        const payload = await request('/games', {
            method: 'POST',
            body: JSON.stringify({
                playerName: elements.playerName.value.trim(),
            }),
        });

        elements.newGameForm.reset();
        showFlash(`Игра #${payload.id} создана.`, 'success');
        await loadGames();
        await loadGame(payload.id);
    } catch (error) {
        showFlash(error.message, 'error');
    }
};

const submitStep = async (event) => {
    event.preventDefault();

    const answerInput = document.getElementById('player-answer');

    try {
        const payload = await request(`/step/${state.currentGame.id}`, {
            method: 'POST',
            body: JSON.stringify({
                answer: answerInput.value.trim(),
            }),
        });

        state.currentGame = payload.game;
        renderCurrentGame();
        await loadGames();
        showFlash(payload.message, 'success');
    } catch (error) {
        showFlash(error.message, 'error');
    }
};

elements.newGameForm.addEventListener('submit', submitNewGame);
elements.reloadGames.addEventListener('click', async () => {
    try {
        await loadGames();
        showFlash('История игр обновлена.', 'success');
    } catch (error) {
        showFlash(error.message, 'error');
    }
});

elements.gamesTableBody.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-game-id]');

    if (!button) {
        return;
    }

    try {
        await loadGame(button.dataset.gameId);
    } catch (error) {
        showFlash(error.message, 'error');
    }
});

const bootstrap = async () => {
    try {
        await loadGames();

        if (state.games[0]) {
            await loadGame(state.games[0].id);
        } else {
            renderCurrentGame();
        }
    } catch (error) {
        showFlash(error.message, 'error');
    }
};

bootstrap();
