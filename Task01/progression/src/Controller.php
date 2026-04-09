<?php

declare(strict_types=1);

namespace Relflly\Progression\Controller;

use function Relflly\Progression\View\renderGreeting;
use function Relflly\Progression\View\renderProgression;
use function Relflly\Progression\View\renderResult;
use function Relflly\Progression\View\renderRules;
use function cli\prompt;

function startGame(): void
{
    renderGreeting();

    $name = trim((string) prompt('May I have your name?', false, ''));
    renderRules($name);

    $gameData = generateGameData();
    renderProgression($gameData['maskedProgression']);

    $answer = trim((string) prompt('Your answer', false, ''));
    $hiddenValue = (string) $gameData['hiddenValue'];
    $isCorrect = $answer === $hiddenValue;

    renderResult(
        $name,
        $isCorrect,
        $answer,
        $hiddenValue,
        $gameData['fullProgression']
    );
}

/**
 * @return array{
 *     maskedProgression: string,
 *     fullProgression: string,
 *     hiddenValue: int
 * }
 */
function generateGameData(): array
{
    $firstNumber = random_int(1, 20);
    $step = random_int(2, 10);
    $length = 10;
    $hiddenIndex = random_int(0, $length - 1);
    $progression = [];

    for ($index = 0; $index < $length; $index++) {
        $progression[] = $firstNumber + ($index * $step);
    }

    $hiddenValue = $progression[$hiddenIndex];
    $maskedProgression = $progression;
    $maskedProgression[$hiddenIndex] = '..';

    return [
        'maskedProgression' => implode(' ', array_map('strval', $maskedProgression)),
        'fullProgression' => implode(' ', array_map('strval', $progression)),
        'hiddenValue' => $hiddenValue,
    ];
}
