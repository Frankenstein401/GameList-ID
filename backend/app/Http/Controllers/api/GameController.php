<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    protected $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'slug' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'platform' => 'required|string',
            'developer' => 'required|string',
            'description' => 'required|string',
            'release_date' => 'required',
            'thumbnail' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ], 422);
        }

        $result = $this->gameService->createGame($request->all(), auth('api')->id());
        return response()->json($result, $result['status'] === 'success' ? 201 : 500);
    }

    public function index(Request $request)
    {
        $result = $this->gameService->getAllGames();
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }

    public function show(Request $request, $slug)
    {
        $validator = Validator::make($request->query(), [
            'slug' => 'required|string'
        ]);

        $result = $this->gameService->getDetailGame($slug);
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }

    public function showTopGames(Request $request)
    {
        $result = $this->gameService->getTopGames();
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }

    public function destroy(Request $request, $slug)
    {
        $validator = Validator::make($request->query(), [
            'slug' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ], 422);
        }

        $result = $this->gameService->deleteGame(auth('api')->id(), $slug);
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }
}
