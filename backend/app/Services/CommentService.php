<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Game;
use App\Models\User;
use Exception;

class CommentService
{
    public function createComment(int $userId, $slug, $data)
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

            $comment = Comment::create([
                'game_id' => $game->id,
                'user_id' => $userId,
                'comment' => $data['comment'],
            ]);

            return [
                'status' => 'success',
                'message' => 'Comment posted successfully',
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}