<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'player_name',
        'progression',
        'hidden_index',
        'hidden_value',
        'answer',
        'is_correct',
        'played_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'progression' => 'array',
            'is_correct' => 'boolean',
            'played_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected function maskedProgression(): Attribute
    {
        return Attribute::get(function (): string {
            $progression = $this->progression ?? [];

            if (! isset($progression[$this->hidden_index])) {
                return '';
            }

            $masked = $progression;
            $masked[$this->hidden_index] = '..';

            return implode(' ', array_map(static fn (mixed $value): string => (string) $value, $masked));
        });
    }

    protected function fullProgression(): Attribute
    {
        return Attribute::get(function (): string {
            return implode(
                ' ',
                array_map(static fn (mixed $value): string => (string) $value, $this->progression ?? [])
            );
        });
    }
}
