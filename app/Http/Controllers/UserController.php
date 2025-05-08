<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Obtiene todas las palabras solicitadas por los usuarios
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersWithRequestedWords()
    {
        try {
            $users = User::with('wordRequests.word')->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron usuarios.'
                ], 404);
            }

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error durante la carga de las palabras solicitadas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene las palabras solicitadas por el usuario autenticado
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUserWithRequestedWords()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            $user = Auth::user()->load('wordRequests.word.category');

            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no autenticado.'
                ], 404);
            }

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'requested_words' => $user->wordRequests->map(function ($request) {
                        return [
                            'word' => $request->word ? $request->word->word : null,
                            'category' => ($request->word && $request->word->category) ? $request->word->category->category_name : null,
                        ];
                    })->filter(function ($request) {
                        return $request['word'] !== null; 
                    }),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrio un error durante la carga',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}