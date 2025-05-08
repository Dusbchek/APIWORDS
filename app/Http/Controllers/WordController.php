<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Models\Category;
use App\Models\Option;
use App\Models\WordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class WordController extends Controller
{
    /**
     * Obtiene una palabra
     */
    public function requestWord(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'word_id' => 'required|exists:words,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación al solicitar palabra.',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (!is_numeric($request->word_id) || $request->word_id <= 0) {
                return response()->json([
                    'message' => 'Por favor ingrese un tipo de dato correcto.',
                    'error' => 'El ID es inválido.'
                ], 400);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado.'], 401);
            }

            $word = Word::find($request->word_id);
            if (!$word) {
                return response()->json(['message' => 'La palabra con el ID proporcionado no fue encontrada.'], 404);
            }

            WordRequest::create([
                'user_id' => $user->id,
                'word_id' => $word->id,
            ]);

            return response()->json([
                'message' => 'Palabra solicitada exitosamente',
                'word' => $word->word,
                'category' => $word->category->category_name,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor al solicitar la palabra.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Añade una nueva palabra con sus opciones
     */
    public function addNewWordWithOptions(Request $request)
    {
        try {
            $request->validate([
                'word' => 'required|string|max:255',
                'category_name' => 'required|string|exists:categories,category_name',
                'options' => 'required|array|min:3',
                'options.*.option' => 'required|string|max:255',
                'options.*.is_correct' => 'required|boolean',
            ]);

            $category = Category::where('category_name', $request->category_name)->first();

            if (!$category) {
                return response()->json(['message' => 'La categoría no fue encontrada.'], 404);
            }

            $word = Word::create([
                'word' => $request->word,
                'category_id' => $category->id,
            ]);

            foreach ($request->options as $optionData) {
                Option::create([
                    'word_id' => $word->id,
                    'option' => $optionData['option'],
                    'is_correct' => $optionData['is_correct'],
                ]);
            }

            return response()->json([
                'message' => 'Palabra y opciones añadidas exitosamente.',
                'word' => $word->word,
                'category' => $category->category_name,
                'options' => $word->options,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene una palabra aleatoria por categoría
     */
    public function getRandomWordByCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required|string|exists:categories,category_name',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error de validación.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            if (!is_string($request->category_name)) {
                return response()->json([
                    'message' => 'El parámetro category_name es inválido.',
                    'error' => 'Debe ser un string.'
                ], 400);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado.'], 401);
            }

            $category = Category::where('category_name', $request->category_name)->first();
            if (!$category) {
                return response()->json(['message' => 'Categoría no encontrada.'], 404);
            }

            $word = $category->words()->inRandomOrder()->with('options')->first();
            if (!$word) {
                return response()->json(['message' => 'No hay palabras en esta categoría.'], 404);
            }

            WordRequest::create([
                'user_id' => $user->id,
                'word_id' => $word->id,
            ]);

            return response()->json([
                'word' => $word->word,
                'category' => $word->category->category_name,
                'word_id' => $word->id,
                'options' => $word->options->map(fn($option) => [
                    'id' => $option->id,
                    'option' => $option->option
                ])
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verifica si una opción es correcta
     */
    public function checkWordOption(Request $request)
    {
        $request->validate([
            'word_id' => 'required|exists:words,id',
            'option_id' => 'required|exists:options,id',
        ]);

        if (!Auth::check()) {
            return response()->json(['message' => 'No estás autenticado.'], 401);
        }

        $user = Auth::user();

        $option = Option::where('id', $request->option_id)
            ->where('word_id', $request->word_id)
            ->first();

        if (!$option) {
            return response()->json(['message' => 'La opción no pertenece a esta palabra.'], 400);
        }

        $isCorrect = (bool) $option->is_correct;

        \App\Models\UserWordResponse::create([
            'user_id' => $user->id,
            'word_id' => $request->word_id,
            'option_id' => $request->option_id,
            'is_correct' => $isCorrect,
        ]);

        return response()->json([
            'correct' => $isCorrect,
            'message' => $isCorrect ? 'Respuesta correcta.' : 'Respuesta incorrecta.'
        ]);
    }

    /**
     * Obtiene una palabra por su ID
     */
    public function getWordById(Request $request, $id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'message' => 'Por favor, introduzca un ID válido.'
                ], 400);
            }

            $word = Word::with(['options', 'category'])->find($id);
            if (!$word) {
                return response()->json(['message' => 'Palabra no encontrada.'], 404);
            }

            return response()->json([
                'word' => $word->word,
                'category' => $word->category->category_name,
                'options' => $word->options->map(fn($option) => [
                    'id' => $option->id,
                    'option' => $option->option
                ])
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene todas las palabras con sus categorías y opciones
     */
    public function getWordsWithOptionsAndCategory()
    {
        try {
            $words = Word::with(['options', 'category'])->get();

            if ($words->isEmpty()) {
                return response()->json(['message' => 'No se encontraron palabras.'], 404);
            }

            return response()->json($words);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene todas las palabras
     */
    public function getWords()
    {
        try {
            $words = Word::all();

            if ($words->isEmpty()) {
                return response()->json(['message' => 'No se encontraron palabras.'], 404);
            }

            return response()->json($words);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
