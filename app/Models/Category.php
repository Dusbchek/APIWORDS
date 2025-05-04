<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Atributos que pueden ser asignados en masa
    protected $fillable = ['category_name'];

    // Relación con las palabras
    public function words()
    {
        return $this->hasMany(Word::class);
    }
}
