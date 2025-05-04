<?php

// database/seeders/WordSeeder.php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Word;
use App\Models\Option;
use Illuminate\Database\Seeder;

class WordSeeder extends Seeder
{
    public function run()
    {
        // Insertar categorías
        $categories = [
            'Tecnología',
            'Ciencia',
            'Arte',
            'Historia',
            'Literatura'
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'category_name' => $categoryName
            ]);
        }

        // Insertar palabras con categorías
        $words = [
            ['word' => 'Átomo', 'category' => 'Ciencia'],
            ['word' => 'Computadora', 'category' => 'Tecnología'],
            ['word' => 'Impresionismo', 'category' => 'Arte'],
            ['word' => 'Renacimiento', 'category' => 'Historia'],
            ['word' => 'Democracia', 'category' => 'Historia'],
            ['word' => 'Evolución', 'category' => 'Ciencia'],
            ['word' => 'Ciberseguridad', 'category' => 'Tecnología'],
            ['word' => 'Surrealismo', 'category' => 'Arte'],
            ['word' => 'Novela', 'category' => 'Literatura'],
            ['word' => 'Filosofía', 'category' => 'Literatura'],
        ];

        foreach ($words as $wordData) {
            $category = Category::where('category_name', $wordData['category'])->first();

            $word = Word::create([
                'word' => $wordData['word'],
                'category_id' => $category->id
            ]);

            // Crear opciones para cada palabra
            $this->createOptionsForWord($word);
        }
    }

    private function createOptionsForWord($word)
    {
        // Opciones para las palabras
        $options = [];

        switch ($word->word) {
            case 'Átomo':
                $options = [
                    ['option' => 'La unidad básica de la materia', 'is_correct' => true], // Correcta
                    ['option' => 'Una forma de energía', 'is_correct' => false], // Incorrecta
                    ['option' => 'Un tipo de célula', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Computadora':
                $options = [
                    ['option' => 'Máquina capaz de realizar operaciones matemáticas', 'is_correct' => false], // Incorrecta
                    ['option' => 'Dispositivo electrónico para procesar información', 'is_correct' => true], // Correcta
                    ['option' => 'Instrumento para medir tiempo', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Impresionismo':
                $options = [
                    ['option' => 'Un estilo de pintura que enfatiza la luz y el color', 'is_correct' => true], // Correcta
                    ['option' => 'Un movimiento musical del siglo XIX', 'is_correct' => false], // Incorrecta
                    ['option' => 'Un tipo de arquitectura medieval', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Renacimiento':
                $options = [
                    ['option' => 'Un período de la historia caracterizado por el renacer del arte clásico', 'is_correct' => true], // Correcta
                    ['option' => 'Un periodo de gran estabilidad política en Europa', 'is_correct' => false], // Incorrecta
                    ['option' => 'Una nueva religión surgida en Europa en el siglo XV', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Democracia':
                $options = [
                    ['option' => 'Sistema de gobierno donde el pueblo tiene el poder de tomar decisiones', 'is_correct' => true], // Correcta
                    ['option' => 'Un gobierno que se basa en un líder absoluto', 'is_correct' => false], // Incorrecta
                    ['option' => 'Sistema económico basado en el mercado libre', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Evolución':
                $options = [
                    ['option' => 'El proceso por el cual los organismos cambian a lo largo del tiempo', 'is_correct' => true], // Correcta
                    ['option' => 'El estudio de la genética de los organismos', 'is_correct' => false], // Incorrecta
                    ['option' => 'La migración de especies de un continente a otro', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Ciberseguridad':
                $options = [
                    ['option' => 'Prácticas para proteger sistemas informáticos de ataques cibernéticos', 'is_correct' => true], // Correcta
                    ['option' => 'El uso de internet para fines educativos', 'is_correct' => false], // Incorrecta
                    ['option' => 'El estudio de las leyes relacionadas con la tecnología', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Surrealismo':
                $options = [
                    ['option' => 'Movimiento artístico que busca expresar el subconsciente', 'is_correct' => true], // Correcta
                    ['option' => 'Un estilo arquitectónico del siglo XVIII', 'is_correct' => false], // Incorrecta
                    ['option' => 'Estilo musical que se originó en Francia', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Novela':
                $options = [
                    ['option' => 'Una obra literaria extensa que narra una historia ficticia', 'is_correct' => true], // Correcta
                    ['option' => 'Un tipo de poema corto', 'is_correct' => false], // Incorrecta
                    ['option' => 'Un artículo periodístico corto', 'is_correct' => false], // Incorrecta
                ];
                break;
            case 'Filosofía':
                $options = [
                    ['option' => 'El estudio de los principios generales que rigen el conocimiento', 'is_correct' => true], // Correcta
                    ['option' => 'La ciencia que estudia los seres vivos', 'is_correct' => false], // Incorrecta
                    ['option' => 'El estudio de las leyes naturales del universo', 'is_correct' => false], // Incorrecta
                ];
                break;
        }

        // Insertar las opciones en la base de datos
        foreach ($options as $optionData) {
            Option::create([
                'word_id' => $word->id,
                'option' => $optionData['option'],
                'is_correct' => $optionData['is_correct']
            ]);
        }
    }
}
