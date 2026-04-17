<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function store(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ], 422);
        }

        $result = $this->ratingService->createComment($slug, auth('api')->id(), $request->all());
        return response()->json($result, $result['status'] === 'success' ? 201 :  500);
    }
}
