<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_main_page_is_available(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Арифметическая прогрессия');
    }

    public function test_game_can_be_created_and_completed(): void
    {
        $createdGame = $this->postJson('/api/games', [
            'player_name' => 'Андрей',
        ])->assertCreated()->json();

        $this->assertSame('Андрей', $createdGame['player_name']);
        $this->assertSame('in_progress', $createdGame['status']);
        $this->assertArrayHasKey('masked_progression', $createdGame);

        $game = Game::findOrFail($createdGame['id']);

        $this->postJson('/api/step/'.$game->id, [
            'answer' => $game->hidden_value,
        ])->assertOk()->assertJsonPath('result.is_correct', true);

        $this->getJson('/api/games')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.status', 'completed');

        $this->getJson('/api/games/'.$game->id)
            ->assertOk()
            ->assertJsonPath('player_name', 'Андрей')
            ->assertJsonPath('is_correct', true)
            ->assertJsonPath('hidden_value', $game->hidden_value);
    }
}
