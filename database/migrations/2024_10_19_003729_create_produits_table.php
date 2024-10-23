<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->text('description');
            $table->decimal('prix', 8, 2);
            $table->integer('quantite');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('categorie_id'); // S'assurer que c'est unsignedBigInteger

            // Définir la clé étrangère après avoir créé les colonnes
            $table->foreign('categorie_id')->references('id')->on('categorie')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits');
    }   
};
