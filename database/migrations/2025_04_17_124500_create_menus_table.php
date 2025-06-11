<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('name'); // "Menu du jour", "Menu midi", etc.
            $table->date('date')->nullable(); // null = menu permanent (ex: carte)
            $table->boolean('is_daily')->default(false); // true = menu du jour, false = carte
            $table->decimal('promotion', 5,2)->nullable(); // Pourcentage de promo sur tout le menu
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
