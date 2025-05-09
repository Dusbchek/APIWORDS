<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\WordRequest;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WordRequestController extends Controller
{

    public function requestWord(Request $request)
    {
        $request->validate([
            'word_id' => 'required|exists:words,id', 
        ]);

        $user = Auth::user();

        $word = Word::findOrFail($request->word_id);

        WordRequest::create([
            'user_id' => $user->id,
            'word_id' => $word->id,
        ]);

        return response()->json([
            'message' => 'Palabra solicitada exitosamente',
            'word' => $word->word,
            'category' => $word->category->category_name,
        ]);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|unique:categories,category_name|max:255',
        ]);
    
        $category = \App\Models\Category::create([
            'category_name' => $request->category_name,
        ]);
    
        return response()->json([
            'message' => 'Categoría añadida exitosamente.',
            'category' => $category
        ]);
    }
    

    public function addNewWordWithOptions(Request $request)
{
    $request->validate([
        'word' => 'required|string|max:255',
        'category_name' => 'required|string|exists:categories,category_name',
        'options' => 'required|array|min:3',
        'options.*.option' => 'required|string|max:255',
        'options.*.is_correct' => 'required|boolean',
    ]);

    $category = Category::where('category_name', $request->category_name)->first();

    $word = Word::create([
        'word' => $request->word,
        'category_id' => $category->id,
    ]);

    foreach ($request->options as $optionData) {
        \App\Models\Option::create([
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
}


    public function checkWordOption(Request $request)
    {
        $request->validate([
            'word_id' => 'required|exists:words,id',
            'option_id' => 'required|exists:options,id',
        ]);
    
       
        if (!Auth::check()) {
            return response()->json([
                'message' => 'No estás autenticado.'
            ], 401); 
        }
    
        $user = Auth::user();
    
        $option = \App\Models\Option::where('id', $request->option_id)
                    ->where('word_id', $request->word_id)
                    ->first();
    
        if (!$option) {
            return response()->json([
                'message' => 'La opción no pertenece a esta palabra.'
            ], 400);
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
    


    public function getRandomWordByCategory(Request $request)
{
    $request->validate([
        'category_name' => 'required|string|exists:categories,category_name',
    ]);

    $user = Auth::user();

    $category = Category::where('category_name', $request->category_name)->firstOrFail();

    $word = Word::with('category', 'options')
                ->where('category_id', $category->id)
                ->inRandomOrder()
                ->first();

    if (!$word) {
        return response()->json(['message' => 'No se encontraron palabras en esta categoría.'], 404);
    }

    WordRequest::create([
        'user_id' => $user->id,
        'word_id' => $word->id,
    ]);

    return response()->json([
        'word' => $word->word,
        'category' => $word->category->category_name,
        'word_id' => $word->id,
        'options' => $word->options->map(function ($option) {
            return [
                'id' => $option->id,
                'option' => $option->option
            ];
        })
    ]);
}


    public function getWordById(Request $request, $id)
    {
        $word = Word::with(['options', 'category'])->findOrFail($id);
    
        return response()->json([
            'word' => $word->word, 
            'category' => $word->category->category_name, // Nombre de la categoría
            'options' => $word->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'option' => $option->option // Mostrar la opción
                ];
            })
        ]);
    }

 

    public function getWordsWithOptionsAndCategory()
    {
        $words = Word::with(['options', 'category'])->get();
        
        return response()->json($words);
    }

    public function getWords()
    {
        $words = Word::all();
        return response()->json($words);
    }

    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function getUsersWithRequestedWords()
    {
        $users = \App\Models\User::with('wordRequests.word')->get();
        
        return response()->json($users);
    }

    public function getAuthenticatedUserWithRequestedWords()
    {
        $user = Auth::user()->load('wordRequests.word.category');
    
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'requested_words' => $user->wordRequests->map(function ($request) {
                    return [
                        'word' => $request->word->word,
                        'category' => $request->word->category->category_name,
                    ];
                }),
            ]
        ]);
    }
    
    
    
}
