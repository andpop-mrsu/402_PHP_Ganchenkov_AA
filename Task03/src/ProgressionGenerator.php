<?php

declare(strict_types=1);

namespace Relflly\Task03;

final class ProgressionGenerator
{
    /**
     * @return array{
     *     progression:string,
     *     maskedProgression:string,
     *     hiddenValue:int
     * }
     */
    public static function generate(): array
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
            'maskedProgression' => implode(' ', array_map('strval', $maskedProgression)),
            'hiddenValue' => $hiddenValue,
        ];
    }
}
