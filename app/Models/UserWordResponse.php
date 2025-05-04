<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWordResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'word_id',
        'option_id',
        'is_correct',
    ];

    // Relación con la tabla de usuarios
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la tabla de palabras
    public function word()
    {
        return $this->belongsTo(Word::class);
    }

    // Relación con la tabla de opciones
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
