<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Rating;
use App\Models\User;
use Exception;

class RatingService
{
    public function createComment($slug, int $userId, $data)
    {
        try {
            $user = User::where('id', $userId)->first();

            if (!$user) {
                return [
                    'status' => 'notfound',
                    'message' => 'Akun tidak ditemukan'
                ];
            }

            $game = Game::where('slug', $slug)->first();

            if ($game->user_id === $userId) {
                return [
                    'status' => 'forbidden',
                    'message' => 'You cannot rate your own game'
                ];
            }

            $rating = Rating::create([
                'game_id' => $game->id,
                'user_id' => $userId,
                'rating' => $data['rating'],
            ]);

            return [
                'status' => 'success',
                'message' => 'Rating successful'
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
