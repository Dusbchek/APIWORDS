<?php

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
            Category::firstOrCreate(['category_name' => $categoryName]);
        }

        // Insertar palabras con categorías
        $words = [
            ['word' => 'Computadora', 'category' => 'Tecnología'],
            ['word' => 'Ciberseguridad', 'category' => 'Tecnología'],
            ['word' => 'Algoritmo', 'category' => 'Tecnología'],
            ['word' => 'Inteligencia Artificial', 'category' => 'Tecnología'],

            ['word' => 'Átomo', 'category' => 'Ciencia'],
            ['word' => 'Evolución', 'category' => 'Ciencia'],
            ['word' => 'Genética', 'category' => 'Ciencia'],
            ['word' => 'Energía', 'category' => 'Ciencia'],

            ['word' => 'Impresionismo', 'category' => 'Arte'],
            ['word' => 'Surrealismo', 'category' => 'Arte'],
            ['word' => 'Cubismo', 'category' => 'Arte'],
            ['word' => 'Barroco', 'category' => 'Arte'],

            ['word' => 'Renacimiento', 'category' => 'Historia'],
            ['word' => 'Democracia', 'category' => 'Historia'],
            ['word' => 'Revolución Francesa', 'category' => 'Historia'],
            ['word' => 'Edad Media', 'category' => 'Historia'],

            ['word' => 'Novela', 'category' => 'Literatura'],
            ['word' => 'Filosofía', 'category' => 'Literatura'],
            ['word' => 'Ensayo', 'category' => 'Literatura'],
            ['word' => 'Poesía', 'category' => 'Literatura'],
        ];

        foreach ($words as $wordData) {
            $category = Category::where('category_name', $wordData['category'])->first();

            $word = Word::firstOrCreate([
                'word' => $wordData['word'],
                'category_id' => $category->id
            ]);

            $this->createOptionsForWord($word);
        }
    }

    private function createOptionsForWord($word)
    {
        $options = [];

        switch ($word->word) {
            case 'Computadora':
                $options = [
                    ['option' => 'Dispositivo electrónico para procesar información', 'is_correct' => true],
                    ['option' => 'Instrumento para medir tiempo', 'is_correct' => false],
                    ['option' => 'Una forma de energía', 'is_correct' => false],
                ];
                break;
            case 'Ciberseguridad':
                $options = [
                    ['option' => 'Prácticas para proteger sistemas informáticos de ataques cibernéticos', 'is_correct' => true],
                    ['option' => 'El uso de internet para fines educativos', 'is_correct' => false],
                    ['option' => 'Una red social', 'is_correct' => false],
                ];
                break;
            case 'Algoritmo':
                $options = [
                    ['option' => 'Conjunto de pasos lógicos para resolver un problema', 'is_correct' => true],
                    ['option' => 'Lenguaje de programación', 'is_correct' => false],
                    ['option' => 'Una función matemática', 'is_correct' => false],
                ];
                break;
            case 'Inteligencia Artificial':
                $options = [
                    ['option' => 'Simulación de procesos humanos por máquinas', 'is_correct' => true],
                    ['option' => 'Un tipo de hardware', 'is_correct' => false],
                    ['option' => 'Lenguaje de programación', 'is_correct' => false],
                ];
                break;

            case 'Átomo':
                $options = [
                    ['option' => 'La unidad básica de la materia', 'is_correct' => true],
                    ['option' => 'Un tipo de célula', 'is_correct' => false],
                    ['option' => 'Una molécula grande', 'is_correct' => false],
                ];
                break;
            case 'Evolución':
                $options = [
                    ['option' => 'El proceso por el cual los organismos cambian a lo largo del tiempo', 'is_correct' => true],
                    ['option' => 'El estudio del clima', 'is_correct' => false],
                    ['option' => 'La migración de especies', 'is_correct' => false],
                ];
                break;
            case 'Genética':
                $options = [
                    ['option' => 'Estudio de la herencia biológica', 'is_correct' => true],
                    ['option' => 'Ciencia de los minerales', 'is_correct' => false],
                    ['option' => 'Estudio de la evolución cultural', 'is_correct' => false],
                ];
                break;
            case 'Energía':
                $options = [
                    ['option' => 'Capacidad para realizar trabajo', 'is_correct' => true],
                    ['option' => 'Materia en estado sólido', 'is_correct' => false],
                    ['option' => 'Elemento químico', 'is_correct' => false],
                ];
                break;

            case 'Impresionismo':
                $options = [
                    ['option' => 'Estilo de pintura que enfatiza la luz y el color', 'is_correct' => true],
                    ['option' => 'Movimiento musical del siglo XIX', 'is_correct' => false],
                    ['option' => 'Tipo de escultura moderna', 'is_correct' => false],
                ];
                break;
            case 'Surrealismo':
                $options = [
                    ['option' => 'Movimiento artístico que busca expresar el subconsciente', 'is_correct' => true],
                    ['option' => 'Estilo arquitectónico del siglo XVIII', 'is_correct' => false],
                    ['option' => 'Estilo musical que se originó en Francia', 'is_correct' => false],
                ];
                break;
            case 'Cubismo':
                $options = [
                    ['option' => 'Estilo artístico que representa objetos con formas geométricas', 'is_correct' => true],
                    ['option' => 'Movimiento poético del siglo XX', 'is_correct' => false],
                    ['option' => 'Técnica de escultura en mármol', 'is_correct' => false],
                ];
                break;
            case 'Barroco':
                $options = [
                    ['option' => 'Estilo artístico caracterizado por la ornamentación', 'is_correct' => true],
                    ['option' => 'Movimiento literario moderno', 'is_correct' => false],
                    ['option' => 'Género de cine histórico', 'is_correct' => false],
                ];
                break;

            case 'Renacimiento':
                $options = [
                    ['option' => 'Período de renacer del arte clásico', 'is_correct' => true],
                    ['option' => 'Nueva religión europea', 'is_correct' => false],
                    ['option' => 'Sistema económico medieval', 'is_correct' => false],
                ];
                break;
            case 'Democracia':
                $options = [
                    ['option' => 'Sistema de gobierno donde el pueblo toma decisiones', 'is_correct' => true],
                    ['option' => 'Gobierno de un monarca absoluto', 'is_correct' => false],
                    ['option' => 'Sistema económico basado en trueque', 'is_correct' => false],
                ];
                break;
            case 'Revolución Francesa':
                $options = [
                    ['option' => 'Conflicto social y político que transformó Francia', 'is_correct' => true],
                    ['option' => 'Tratado de paz en Europa', 'is_correct' => false],
                    ['option' => 'Una guerra contra Inglaterra', 'is_correct' => false],
                ];
                break;
            case 'Edad Media':
                $options = [
                    ['option' => 'Período entre la caída del Imperio Romano y el Renacimiento', 'is_correct' => true],
                    ['option' => 'Periodo moderno de industrialización', 'is_correct' => false],
                    ['option' => 'Era espacial del siglo XX', 'is_correct' => false],
                ];
                break;

            case 'Novela':
                $options = [
                    ['option' => 'Obra literaria extensa que narra una historia ficticia', 'is_correct' => true],
                    ['option' => 'Poema lírico corto', 'is_correct' => false],
                    ['option' => 'Ensayo científico', 'is_correct' => false],
                ];
                break;
            case 'Filosofía':
                $options = [
                    ['option' => 'Estudio de los principios generales del conocimiento', 'is_correct' => true],
                    ['option' => 'Ciencia de los seres vivos', 'is_correct' => false],
                    ['option' => 'Estudio de minerales', 'is_correct' => false],
                ];
                break;
            case 'Ensayo':
                $options = [
                    ['option' => 'Texto que analiza o interpreta un tema', 'is_correct' => true],
                    ['option' => 'Noticia periodística', 'is_correct' => false],
                    ['option' => 'Fábula breve', 'is_correct' => false],
                ];
                break;
            case 'Poesía':
                $options = [
                    ['option' => 'Expresión literaria que usa el verso y la métrica', 'is_correct' => true],
                    ['option' => 'Texto técnico sobre historia', 'is_correct' => false],
                    ['option' => 'Narración de hechos reales', 'is_correct' => false],
                ];
                break;
        }

        foreach ($options as $optionData) {
            Option::create([
                'word_id' => $word->id,
                'option' => $optionData['option'],
                'is_correct' => $optionData['is_correct']
            ]);
        }
    }
}
