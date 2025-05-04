<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    // Atributos que pueden ser asignados en masa
    protected $fillable = ['word', 'category_id'];


    
    // Relación con las opciones
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con el registro de solicitudes
    public function wordRequests()
    {
        return $this->hasMany(WordRequest::class);
    }
}
