<?php

namespace App\Services;

use App\Models\Game;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class GameService
{
    public function getAllGames()
    {
        try {
            $games = Game::with('user', 'category', 'ratings')->get();

            $data = $games->map(function ($game) {
                return [
                    'id' => $game->id,
                    'title' => $game->title,
                    'slug' => $game->slug,
                    'description' => $game->description,
                    'platform' => $game->platform,
                    'developer' => $game->developer,
                    'release_date' => $game->release_date,
                    'thumbnail' => $game->thumbnail,
                    'created_at' => $game->created_at,
                    'author' => $game->user->username,
                    'rating_avg' => round($game->ratings->avg('rating'), 1),
                    'category' => [
                        'id' => $game->category->id,
                        'name' => $game->category->name,
                        'slug' => $game->category->slug
                    ]
                ];
            });

            return [
                'status' => 'success',
                'message' => 'Get all games successful',
                'data' => [
                    'games' => $data
                ]
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getDetailGame($slug)
    {
        try {
            $game = Game::with('user', 'category', 'ratings', 'comments')->where('slug', $slug)->first();

            return [
                'status' => 'success',
                'message' => 'Get all detail game successful',
                'data' => [
                    'id' => $game->id,
                    'title' => $game->title,
                    'slug' => $slug,
                    'description' => $game->description,
                    'platform' => $game->platform,
                    'developer' => $game->developer,
                    'release_date' => $game->release_date,
                    'thumbnail' => $game->thumbnail,
                    'created_at' => $game->created_at,
                    'author' => $game->user->username,
                    'rating_avg' => round($game->ratings->avg('rating'), 1),
                    'category' => [
                        'id' => $game->category->id,
                        'name' => $game->category->name,
                        'slug' => $game->category->slug
                    ],
                    'comments' => $game->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'comment' => $comment->comment,
                            'created_at' => $comment->created_at,
                            'comment_author' => $comment->user->username
                        ];
                    })
                ]
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getTopGames()
    {
        try {
            $games = Game::with('user', 'category', 'ratings')->withAvg('ratings', 'rating')->orderByDesc('ratings_avg_rating')->take(3)->get();

            $data = $games->map(function ($game) {
                return [
                    'id' => $game->id,
                    'title' => $game->title,
                    'slug' => $game->slug,
                    'description' => $game->description,
                    'platform' => $game->platform,
                    'developer' => $game->developer,
                    'release_date' => $game->release_date,
                    'thumbnail' => $game->thumbnail,
                    'created_at' => $game->created_at,
                    'author' => $game->user->username,
                    'rating_avg' => round($game->ratings->avg('rating'), 1),
                    'category' => [
                        'id' => $game->category->id,
                        'name' => $game->category->name,
                        'slug' => $game->category->slug,
                    ]
                ];
            });

            return [
                'status' => 'success',
                'message' => 'Get top 3 games successful',
                'data' => [
                    'best_games' => $data
                ]
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function createGame($data, int $userId)
    {
        try {
            $user = User::where('id', $userId)->first();

            if (!$user) {
                return [
                    'status' => 'notfound',
                    'message' => 'Akun tidak ditemukan'
                ];
            }

            DB::beginTransaction();
            $game = Game::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'category_id' => $data['category_id'],
                'user_id' => $userId,
                'platform' => $data['platform'],
                'developer' => $data['developer'],
                'description' => $data['description'],
                'release_date' => $data['release_date'],
                'thumbnail' => $data['thumbnail']
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Create game successfully'
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteGame(int $userId, $slug)
    {
        try {
            $user = User::where('id', $userId)->first();

            if (!$user) {
                return [
                    'status' => 'notfound',
                    'message' => 'Akun tidak ditemukan'
                ];
            }

            if($userId != $user->id) {
                return [
                    'status' => 'forbidden',
                    'message' => 'Forbidden access'
                ];
            }

            $game = Game::where('slug', $slug)->first();
            $game->delete();

            return [
                'status' => 'success',
                'message' => 'Game deleted successful'
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
