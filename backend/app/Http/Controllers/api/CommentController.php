<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ],  422);
        }

        $result = $this->commentService->createComment(auth('api')->id(), $slug, $request->all());
        return response()->json($result, $result['status'] === 'success' ? 200 :500);
    }
}
