<?php

declare(strict_types=1);

/**
 * @return array{
 *     progression: string,
 *     masked_progression: string,
 *     hidden_value: int
 * }
 */
function generateProgressionData(): array
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
        'progression' => implode(' ', array_map('strval', $progression)),
        'masked_progression' => implode(' ', array_map('strval', $maskedProgression)),
        'hidden_value' => $hiddenValue,
    ];
}
