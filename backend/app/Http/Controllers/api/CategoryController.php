<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'slug' => 'required|string',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ], 422);
        }

        $result = $this->categoryService->createCategory($request->all(), auth('api')->id());
        return response()->json($result, $result['status'] === 'success' ? 201 : 500);
    }

    public function index(Request $request)
    {
        $result = $this->categoryService->getAllCategory();
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }

    public function destroy(Request $request, $slug)
    {
        $validator = Validator::make($request->query(), [
            'slug' => 'string'
        ]);

        $result = $this->categoryService->deleteCategory($slug, auth('api')->id());
        return response()->json($result, $result['status'] === 'success' ? 200 : 500);
    }
}
