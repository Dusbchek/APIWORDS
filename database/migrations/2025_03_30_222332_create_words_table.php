<?php

// database/migrations/xxxx_xx_xx_create_words_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();  // Crea un 'bigIncrements' que es 'unsigned' por defecto
            $table->string('word');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');  // La clave foránea a la tabla 'categories'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
