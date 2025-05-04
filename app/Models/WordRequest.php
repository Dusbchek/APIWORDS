<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordRequest extends Model
{
    use HasFactory;

    // Atributos que pueden ser asignados en masa
    protected $fillable = ['user_id', 'word_id'];

    // Relación con el usuario autenticado
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la palabra solicitada
    public function word()
    {
        return $this->belongsTo(Word::class);
    }


    
}
