<?php

declare(strict_types=1);

namespace Relflly\Progression\View;

use function cli\line;

function renderGreeting(): void
{
    line('Progression game');
    line('================');
}

function renderRules(string $name): void
{
    $playerName = $name === '' ? 'player' : $name;

    line('Hello, %s!', $playerName);
    line('Find the missing number in the arithmetic progression.');
}

function renderProgression(string $progression): void
{
    line('Question: %s', $progression);
}

function renderResult(
    string $name,
    bool $isCorrect,
    string $answer,
    string $hiddenValue,
    string $fullProgression
): void {
    $playerName = $name === '' ? 'player' : $name;

    if ($isCorrect) {
        line('Correct!');
        line('Congratulations, %s!', $playerName);
        return;
    }

    line('"%s" is wrong answer ;(. Correct answer was "%s".', $answer, $hiddenValue);
    line('Full progression: %s', $fullProgression);
    line('Let''s try again, %s!', $playerName);
}
