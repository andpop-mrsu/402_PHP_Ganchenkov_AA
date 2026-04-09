<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class GameController extends Controller
{
    public function index(): JsonResponse
    {
        $games = Game::query()
            ->latest('played_at')
            ->get()
            ->map(fn (Game $game): array => $this->gamePayload($game, true))
            ->all();

        return response()->json($games);
    }

    public function show(Game $game): JsonResponse
    {
        return response()->json($this->gamePayload($game, false, $game->completed_at !== null));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'player_name' => ['required', 'string', 'max:100'],
        ]);

        $playerName = trim($validated['player_name']);

        if ($playerName === '') {
            throw ValidationException::withMessages([
                'player_name' => 'The player name field is required.',
            ]);
        }

        $progression = $this->generateProgression();

        $game = Game::query()->create([
            'player_name' => $playerName,
            'progression' => $progression['progression'],
            'hidden_index' => $progression['hidden_index'],
            'hidden_value' => $progression['hidden_value'],
            'played_at' => Carbon::now(),
        ]);

        return response()->json($this->gamePayload($game), 201);
    }

    /**
     * @throws ValidationException
     */
    public function step(Request $request, Game $game): JsonResponse
    {
        if ($game->completed_at !== null) {
            throw ValidationException::withMessages([
                'game' => 'The selected game is already completed.',
            ]);
        }

        $validated = $request->validate([
            'answer' => ['required', 'integer'],
        ]);

        $answer = (int) $validated['answer'];
        $game->fill([
            'answer' => $answer,
            'is_correct' => $answer === $game->hidden_value,
            'completed_at' => Carbon::now(),
        ]);
        $game->save();

        return response()->json([
            'message' => $game->is_correct
                ? sprintf('Correct! Congratulations, %s!', $game->player_name)
                : sprintf(
                    '"%s" is wrong answer ;(. Correct answer was "%s".',
                    $answer,
                    $game->hidden_value
                ),
            'result' => [
                'is_correct' => $game->is_correct,
                'player_answer' => $game->answer,
                'hidden_value' => $game->hidden_value,
                'full_progression' => $game->full_progression,
            ],
            'game' => $this->gamePayload($game, false, true),
        ]);
    }

    /**
     * @return array{progression: array<int, int>, hidden_index: int, hidden_value: int}
     */
    private function generateProgression(): array
    {
        $firstNumber = random_int(1, 20);
        $step = random_int(2, 10);
        $length = 10;
        $hiddenIndex = random_int(0, $length - 1);
        $progression = [];

        for ($index = 0; $index < $length; $index++) {
            $progression[] = $firstNumber + ($index * $step);
        }

        return [
            'progression' => $progression,
            'hidden_index' => $hiddenIndex,
            'hidden_value' => $progression[$hiddenIndex],
        ];
    }

    private function gamePayload(Game $game, bool $summary = false, bool $revealAnswer = false): array
    {
        $payload = [
            'id' => $game->id,
            'player_name' => $game->player_name,
            'masked_progression' => $game->masked_progression,
            'player_answer' => $game->answer,
            'is_correct' => $game->is_correct,
            'played_at' => $game->played_at?->toIso8601String(),
            'completed_at' => $game->completed_at?->toIso8601String(),
            'status' => $game->completed_at === null ? 'in_progress' : 'completed',
        ];

        if ($revealAnswer) {
            $payload['full_progression'] = $game->full_progression;
            $payload['hidden_value'] = $game->hidden_value;
        }

        if ($summary) {
            unset($payload['full_progression'], $payload['hidden_value']);
        }

        return $payload;
    }
}
