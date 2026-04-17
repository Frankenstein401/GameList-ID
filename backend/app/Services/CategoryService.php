<?php

namespace App\Services;

use App\Models\User;
use App\Models\Category;
use Exception;

class CategoryService
{
    public function getAllCategory()
    {
        try {
            $categories = Category::all();

            return [
                'status' => 'success',
                'message' => 'Get all categories success',
                'data' => $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug
                    ];
                })
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function createCategory($data, int $userId)
    {
        try {
            $user = User::where('id', $userId)->where('role', 'admin')->first();

            if (!$user) {
                return [
                    'status' => 'notfound',
                    'message' => 'Akun tidak ditemukan'
                ];
            }

            if ($user->role === 'user') {
                return [
                    'status' => 'forbidden',
                    'message' => 'kamu tidak memiliki akses'
                ];
            }

            $category = Category::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
            ]);

            return [
                'status' => 'success',
                'message' => 'Create category successful'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteCategory($slug, int $userId)
    {
        try {
            $user = User::where('id', $userId)->where('role', 'admin')->first();

            if(!$user) {
                return [
                    'status' => 'notfound',
                    'message' => 'User not found'
                ];
            }

            if($user->role != 'admin') {
                return [
                    'status' => 'forbidden',
                    'message' => 'Forbidden access'
                ];
            }

            $category = Category::where('slug', $slug)->first();
            $category->delete();

            return [
                'status' => 'success',
                'message' => 'Deleted category successful'
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
