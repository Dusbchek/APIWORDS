<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    // Atributos que pueden ser asignados en masa
    protected $fillable = ['option', 'word_id', 'is_correct'];

    // Relación con la palabra
    public function word()
    {
        return $this->belongsTo(Word::class);
    }
}
