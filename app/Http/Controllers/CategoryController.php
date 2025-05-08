<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Añade una categoría nueva
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCategory(Request $request)
    {
        try {
            $request->validate([
                'category_name' => 'required|string|unique:categories,category_name|max:255',
            ], [
                'category_name.required' => 'Please enter the category name.',
                'category_name.string' => 'The category name must be a string.',
                'category_name.unique' => 'The category name already exists.',
                'category_name.max' => 'The category name cannot exceed 255 characters.',
            ]);

            $category = Category::create([
                'category_name' => $request->category_name,
            ]);

            return response()->json([
                'message' => 'Categoría añadida exitosamente.',
                'category' => $category
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error adding category: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while adding the category. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene todas las categorias
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        try {
            $categories = Category::all();

            if ($categories->isEmpty()) {
                return response()->json([
                    'message' => 'No categories found.'
                ], 404);
            }

            return response()->json($categories);

        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching categories. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}